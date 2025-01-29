<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\V1\OptAuthCodeController;
use App\Models\Api\V1\OptAuthCode;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureOtpMiddleware
{
    private $optAuthCode;
    public function __construct(OptAuthCode $optAuthCode){
        $this->optAuthCode = $optAuthCode;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$this->optAuthCode->otp_code) { // no otp
            if($this->optAuthCode->otp_code && !$this->optAuthCode->otp_code_expire_at->lt(now())) {
                //redirect to upload page
                $this->optAuthCode->resetOTP(); //clear OTP in database
                // return redirect()->route('templates.store')->withMessage('The to factor code has expired. Please request a new one');
            }
        
            
            // if(!$request->is('verify*') ) {}
            return redirect()->action([OptAuthCodeController::class,'requestOTP']);
            // return redirect('/requestOtp');
        }
        return $next($request);
    }
}