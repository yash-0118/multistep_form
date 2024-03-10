<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Models\FormData;
use App\Mail\SendOtpMail;

class RegisterController extends Controller
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
        ]);

        // Mail::to($data['email'])->send(new App\Http\Controllers\SendOtpMail($otp));

        return redirect('/form/step2');
    }

    public function step2(Request $request)
    {
        $savedData = $request->session()->get('form_data');
        

        if (!$savedData) {
            return redirect('/form/step1');
        }

        return view('form.step2');
    }

    public function postStep2(Request $request)
    {
        $data = $request->validate([
            'otp' => 'required|numeric',
        ]);

        $savedData = $request->session()->get('form_data');
        

        if ($data['otp'] == $savedData['otp']) {
            // $request->session()->forget(['form_data', 'form_progress']);
            return view('form.step3');
        } else {
            return redirect('/form/step2')->with('error', 'Incorrect OTP. Please try again.');
        }
    }

    public function step3(Request $request)
    {
        return view('form.step3');
    }

    public function postStep3(Request $request)
    {
        $data = $request->validate([
            'property_number' => 'required|unique:form_data,property_number',
            'business_name' => 'required',
            'address' => 'required',
            'business_trn' => 'required',
        ]);

        $formData = $request->session()->get('form_data', []);
        
        $formData[] = $data;
        $request->session()->put('form_data', $formData);

        return view('form.step3');
    }

    public function step4(Request $request)
    {
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
        if ($this->allFieldsFilled($data)) {
            return view('form.step5');
        } else {
            return back()->with('error', 'Please fill in all fields.');
        }
    }

    public function step5(Request $request)
    {
        return view('form.step5');
    }

    public function postStep5(Request $request)
    {
        $request->validate([
            'declaration' => 'required',
        ]);

        if ($request->input('declaration')) {
            return view('form.step6');
        } else {
            return back()->with('error', 'Please accept the declaration.');
        }
    }

    public function step6(Request $request)
    {
        return view('form.step6');
    }

    public function postStep6(Request $request)
    {
        $formData = $request->session()->get('form_data', []);

        unset($formData['otp']);
        $flattenedData = [];
        foreach ($formData as $key => $value) {
            if (is_array($value)) {
                $flattenedData = array_merge($flattenedData, $value);
            } else {
                $flattenedData[$key] = $value;
            }
        }

        // Create a new record in the 'formdata' table
        Formdata::create($flattenedData);

        // Clear session data
        $request->session()->forget(['form_data', 'form_progress']);

        return view('form.confirmation');
    }

}
