<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Api\V1\OptAuthCode;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class OptAuthCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, OptAuthCode $optAuthCode)
    {
        //verify OTP code
        $request->validate([
            'otp_code' => 'required|integer|digits:4',
        ]);

        //compare request-code with code in db.
        //if match, return a redierct to uplaod page.
        $isValidOtp = $optAuthCode->where('otp_code', $request->input('otp_code'))->get();
        if ($isValidOtp->count() === 0) {
            return response([
                "errorMessage" => 'Invalid Otp',
            ]);
        }
        
        $isOtpExpired = Carbon::parse($isValidOtp[0]->otp_code_expire_at)->gt(Carbon::now());

        if(!$isOtpExpired) {
            return response([
                "errorMessage" => 'Otp Expired',
            ]);
        }
        
        $optAuthCode->resetOTP();
        // return response([
        //     'message' => "redirect successful"
        // ]);
        return redirect()->action([TemplateController::class, 'store'])->with(['message' => 'success']);
        
    }

    /**
     * Display the specified resource.
     */
    public function requestOTP(OptAuthCode $optAuthCode)
    {
        try {

            $optAuthCode->truncate();
            //generate OTP code and send code to user email.
            $optAuthCode->generateOTP();
           
            return response([
                "message" => "OTP Sent successfully"
            ]);
            // return redirect()->route('otp.store')->with(['message' => 'OTP code sent successfully', 'otp' => $optAuthCode]);
        } catch(Exception $exception) {
            return response([
                "errorMessage" => "Message not sent",
            ]);
        }
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}