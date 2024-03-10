<?php

namespace App\Http\Controllers;

use App\Models\Formdata;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class FormController extends Controller
{
    public function step1()
    {
        return view('form.step1');
    }

    public function postStep1(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);
        $otp = mt_rand(1000, 9999);

        $request->session()->put('form_data', [
            'email' => $data['email'],
            'otp' => $otp,
            'file_name' => null,
        ]);

        Mail::to($data['email'])->send(new \App\Mail\MyTestMail($otp));
        $request->session()->put('form_progress.step1', 1);

        return redirect('/form/step2');
    }

    public function step2(Request $request)
    {
        $savedData = $request->session()->get('form_data');
        if (!$savedData || !isset($savedData['email'])) {
            return redirect('/form/step1')->with('error', 'Please complete this step first.');
        }
        $savedData = $request->session()->get('form_data');
        return view('form.step2');
    }

    public function postStep2(Request $request)
    {
        $data = $request->validate([
            'otp' => 'required|numeric',
        ]);

        $savedData = $request->session()->get('form_data');
        

        if ($data['otp'] == $savedData['otp']) {
            $request->session()->put('form_progress.step2', 2);
            return redirect('/form/step3');
        } else {
            return redirect('/form/step2')->with('error', 'Incorrect OTP. Please try again.');
        }
    }
    public function step3(Request $request)
    {
        $savedData = $request->session()->get('form_data');
        if (!isset($savedData['email'])) {
            return redirect('/form/step1')->with('error', 'Please complete this step first.');
        }


        return view('form.step3');
    }

    public function postStep3(Request $request)
    {
        $data = $request->validate([
            'property_number.*' => 'required',
            'business_name.*' => 'required',
            'address.*' => 'required',
            'business_trn.*' => 'required',
        ]);

        $formData = $request->session()->get('form_data', []);

        // Initialize an array to store properties
        $properties = [];

        // Iterate over the submitted properties
        for ($i = 0; $i < count($data['property_number']); $i++) {
            $properties[] = [
                'property_number' => $data['property_number'][$i],
                'business_name' => $data['business_name'][$i],
                'address' => $data['address'][$i],
                'business_trn' => $data['business_trn'][$i],
            ];
        }

        // Store the array of properties
        $formData['property'] = $properties;


        $request->session()->put('form_data', $formData);
        $request->session()->put('form_progress.step3', 3);
        return redirect('/form/step4');
    }




    public function step4(Request $request)
    {
        $savedData = $request->session()->get('form_data');

        if (!isset($savedData['email'])) {
            return redirect('/form/step1')->with('error', 'Please complete this step first.');
        }
        if (!$savedData || !isset($savedData['email']) || !isset($savedData['property'])) {
            return redirect('/form/step3')->with('error', 'Please complete this step first.');
        }

        return view('form.step4');
    }

    public function postStep4(Request $request)
    {
        $data = $request->validate([
            'bank_name' => 'required',
            'account_number' => 'required',
            'branch' => 'required',
            'ifsc_code' => 'required',
        ]);

        $formData = $request->session()->get('form_data', []);
        $formData['bank_details'] = $data;
        $request->session()->put('form_data', $formData);
        $request->session()->put('form_progress.step4', 4);

        return redirect('/form/step5');
    }
    public function step5(Request $request)
    {
        $savedData = $request->session()->get('form_data');
        if(!isset($savedData['email'])){
            return redirect('/form/step1')->with('error', 'Please complete this step first.'); 
        }

        if (!$savedData || !isset($savedData['email']) || !isset($savedData['property']) || !isset($savedData['bank_details'])) {
            return redirect('/form/step4')->with('error', 'Please complete this step first.');
        }
        return view('form.step5');
    }

    public function postStep5(Request $request)
    {
        $data = $request->validate([
            'declaration' => 'required',
        ]);
        $formData = $request->session()->get('form_data');
        $formData['declaration'] = $data['declaration'];

        
        if ($request->hasFile('pdf')) {
            $request->validate([
                'pdf' => 'mimes:pdf|max:10240',
            ]);
            $pdfFile = $request->file('pdf');
            $pdfFileName = time() . '_' . $pdfFile->getClientOriginalName();
            $pdfPath = $pdfFile->storeAs('pdfs', $pdfFileName);
            $formData['file_name'] = $pdfFileName;
        }
        unset($formData['otp']);

        $request->session()->put('form_data', $formData);
        return redirect('form/step6');
    }
    public function step6(Request $request)
    {
        $savedData = $request->session()->get('form_data');
        if (!$savedData || !isset($savedData['email']) || !isset($savedData['property']) || !isset($savedData['bank_details']) || !isset($savedData['declaration'])) {
            return redirect('/form/step5')->with('error', 'Please complete this step first.');
        }


        $formData = $request->session()->get('form_data', []);
        // dd($formData);
        $flattenedData = [];
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $flattenedData = array_merge($flattenedData, $value);
            } else {
                $flattenedData[$key] = $value;
            }
        }
        $request->session()->put('form_progress.step5', 5);
        if (isset($formData['declaration']) || $formData['file_name']) {
            return view('form.step6', ['storedData' => $formData]);
        } else {
            return back()->with('error', 'Please accept the declaration.');
        }
    }
    public function postStep6(Request $request)
    {
        $formData = $request->session()->get('form_data', []);
        $propertyDetails = $formData['property'];
        unset($formData['otp']);

        Formdata::create([
            'email' => $formData['email'],
            'property' => json_encode($propertyDetails),
            'bank_name' => $formData['bank_details']['bank_name'],
            'account_number' => $formData['bank_details']['account_number'],
            'branch' => $formData['bank_details']['branch'],
            'ifsc' => $formData['bank_details']['ifsc_code'],
            'file_name' => $formData['file_name'] ?? null,
        ]);

        $request->session()->put('form_progress.step6', 6);
        $request->session()->forget(['form_data', 'form_progress']);

        return view('form.confirmation', ['storedData' => $formData]);
    }
}
