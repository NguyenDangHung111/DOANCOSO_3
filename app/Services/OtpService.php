<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Message;
use App\Models\Accounts;
use Carbon\Carbon;

class OtpService
{

    public function __construct(){
      
    }


    public function sendOtp(Request $request)
    {
        $otp = mt_rand(1000, 9999); // Sinh mã OTP ngẫu nhiên
    
        $email = $request->email; // Email của người dùng
    
        // Nội dung HTML của email
        $htmlContent = '
            <!DOCTYPE html>
            <html lang="en">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <title>Mã OTP của bạn</title>
            </head>
            <body>
                <p>Xin chào,</p>
                
                <p>Dưới đây là mã OTP của bạn:</p>
                
                <h2 style="background-color: #f8f8f8; padding: 10px; font-size: 24px; border-radius: 5px;">'.$otp.'</h2>
                
                <p>Vui lòng sử dụng mã này để hoàn tất quá trình xác thực.</p>
                
                <p>Trân trọng,</p>
                <p>Fast Food</p>
            </body>
            </html>
        ';
    
        // Gửi email chứa mã OTP
        Mail::send([], [], function (Message $message) use ($email, $htmlContent) {
            $message->to($email)
                    ->subject('Fast Food - Mã xác minh')
                    ->html($htmlContent);
        });
    
        return $this->changeOtp($email, $otp);
    }
    
    

    public function changeOtp($email,$otp){
        //update otp in database
        $account = Accounts::where('email', $email)->first();

        if ($account) {
            $account->otp = $otp;
            $account->otp_created_at = Carbon::now();
            $account->save();
            return true;
        } else {
            return false;
        }
    }

    public function checkTimeOtp($email){
        $account = Accounts::where('email', $email)->first();
        $now = Carbon::now();
        $created_at = Carbon::parse($account->otp_created_at);
        $diff = $now->diffInSeconds($created_at);
        if($diff > 120){
            return false;
        }else{
            return true;
        }
    }

    public function checkOtp($email,$otp){
        //check otp in database
        $account = Accounts::where('email', $email)->first();
        if($account->otp == $otp && $this->checkTimeOtp($email)){
           return true;
        }else{
            return false;
        }
        
    }

    
}
