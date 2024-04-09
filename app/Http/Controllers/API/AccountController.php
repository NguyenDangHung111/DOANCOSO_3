<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\Interfaces\AccountServiceInterface as AccountService;
use App\Services\OtpService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class AccountController extends Controller
{
    private AccountService $accountService;
    private OtpService $sendOtp;

    public function __construct(AccountService $accountService)
    {
        $this->accountService = $accountService;
        $this->sendOtp = new OtpService();
    }
    public function get_accounts()
    {
        return $this->accountService->get_accounts();
    }

    public function create_account(Request $request)
    {
        if ($this->accountService->create_account($request)) {
            return response()->json(['message' => 'Customer added successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to add customer'], 500);
        }
    }

    public function login(Request $request)
    {
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::guard('account')->attempt($credentials)) {
            $user = Auth::guard('account')->user();

            if ($user->status == 0) {
                Auth::guard('account')->logout();
                return redirect();
            } elseif ($user->status == 1) {
                return response()->json(['message' => 'Login successfully'], 200);
            } elseif ($user->function == 2) {
                return redirect();
            }
        }
        return response()->json(['error' => 'Unauthorized'], 401);
    }

    public function checkEmail(Request $request)
    {
        if ($this->accountService->checkEmail($request)) {
            return response()->json(['message' => 'Email already exists'], 200);
        } else {
            return response()->json(['message' => 'Email does not exist'], 401);
        }

    }

    public function sendOtp(Request $request)
    {
        if ($this->sendOtp->sendOtp($request)) {
            response()->json(['message' => 'OTP sent'], 200);
        } else {
            return response()->json(['message' => 'Failed to send OTP'], 500);
        }

    }

    public function checkOtp(Request $request)
    {
        if ($this->sendOtp->checkOtp($request->email, $request->otp)) {
            return response()->json(['message' => 'OTP is correct'], 200);
        }
        return response()->json(['message' => 'OTP is incorrect'], 401);
    }

    public function forgotPassword(Request $request)
    {
        if ($this->accountService->forgotPassword($request)) {
            return response()->json(['message' => 'Password changed successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to change password'], 500);
        }
    }

    public function deleteAccount(Request $request)
    {
        if ($this->accountService->deleteAccount($request)) {
            return response()->json(['message' => 'Account deleted successfully'], 201);
        } else {
            return response()->json(['message' => 'Failed to delete account'], 500);
        }
    }

    public function load()
    {
        return view('load');
    }

    public function to_Google()
    {
        try {
            return Socialite::driver('google')->redirect();
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function callBack_to_Google()
    {
        try {
           dd(Socialite::driver('google')->user());
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function to_Facebook()
    {
        try {
            return Socialite::driver('facebook')->redirect();
        } catch (\Exception $exception) {
            return $exception;
        }
    }

    public function callBack_to_Facebook()
    {
        try {
           dd(Socialite::driver('facebook')->user());
        } catch (\Exception $exception) {
            return $exception;
        }
    }
}
