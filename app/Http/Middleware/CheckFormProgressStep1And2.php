<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckFormProgressStep1And2
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $formProgressStep1 = $request->session()->get('form_progress.step1', 0);
        $formProgressStep2 = $request->session()->get('form_progress.step2', 0);
        // dd($formProgressStep1 , $formProgressStep2);

        if ($formProgressStep2 == 2 and $formProgressStep1 == 1) {
            return redirect('/form/step3')->with('error', "Now You can't Go On step 1 or 2");
        }
        return $next($request);
    }
}
