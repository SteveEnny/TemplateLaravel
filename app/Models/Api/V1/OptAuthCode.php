<?php

namespace App\Models\Api\V1;

use App\Models\User;
use App\Notifications\OtpRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptAuthCode extends Model
{
    use HasFactory;

    protected $fillable = ['otp_code'];

    protected $dates = ['otp_code_expire_at'];

    public function generateOTP() {
        $this->timestamps = false;
        $this->otp_code = rand(1000, 9999);
        $this->otp_code_expire_at = now()->addMinutes(5);
        
        // Mail otp
        // $this->mailOTP();
        $user = User::find(1);
        $user->notify(new OtpRequest($this->otp_code, $this->otp_code_expire_at));
        $this->save();
        // return $user;       
    }
    public function resetOTP() {
        $this->timestamps = false;
        $this->otp_code = null;
        $this->otp_code_expire_at = null;
        // $this->save();
    }

    private function mailOTP() {
        $user = User::find(1);
        $user->notify(new OtpRequest($this->otp_code, $this->otp_code_expire_at));
        return $user;
    }
}