<?php

namespace App\Models\Api\V1;

use App\Models\User;
use App\Notifications\OtpRequest;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Notification;

class OptAuthCode extends Model
{
    use HasFactory;

    protected $fillable = ['otp_code'];

    protected $dates = ['otp_code_expire_at'];

    public function generateOTP($userId) {
        $this->timestamps = false;
        $this->otp_code = rand(1000, 9999);
        $this->user_id = $userId;
        $this->otp_code_expire_at = now()->addMinutes(5);
        $this->save();
        
        // Mail otp
        $this->mailOTP();
       


        // User::find(1)->notify(new OtpRequest($this->otp_code, $this->otp_code_expire_at));
        // return $user;       
    }
    public function resetOTP() {
        $this->timestamps = false;
        $this->otp_code = null;
        $this->otp_code_expire_at = null;
        $this->save();
    }

    public function createOtp($userId)
    {
         $otp_code = rand(1000, 9999);
         $otp_code_expire_at = now()->addMinutes(5);
        $this->create([
            'otp_code' => $otp_code,
            'otp_code_expire_at' => $otp_code_expire_at,
            'user_id' => $userId,
            'timestamps' => false,
        ]);
    }

    private function mailOTP() {
        // $user = User::find(1);
        // $user->notify(new OtpRequest($this->otp_code, $this->otp_code_expire_at));
        // return $user;

        $user = User::find(1);
        Notification::send($user, new OtpRequest($this->otp_code, $this->otp_code_expire_at));
    }

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}