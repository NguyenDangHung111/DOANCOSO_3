<?php

namespace App\Services;

use App\Services\Interfaces\AccountServiceInterface;
use App\Models\Accounts;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AccountService implements AccountServiceInterface
{
    public function get_accounts()
    {
        $accounts = Accounts::all();

        // Định dạng lại thời gian trong mỗi bản ghi
        $formattedAccounts = $accounts->map(function ($account) {

            return [
                'id' => $account->id,
                'oauth_provider' => $account->oauth_provider,
                'oauth_uid' => $account->oauth_uid,
                'image' => $account->image,
                'first_name' => $account->first_name,
                'last_name' => $account->last_name,
                'gender' => $account->gender,
                'email' => $account->email,
                'password' => $account->password,
                'phone' => $account->phone,
                'address' => $account->address,
                'created_at' => Carbon::parse($account->created_at)->toDateTimeString(),
                'updated_at' => Carbon::parse($account->updated_at)->toDateTimeString(),
            ];
        });

        return response()->json($formattedAccounts);
    }

    public function create_account($request)
    {
        try {
            $account = Accounts::where('oauth_uid', $request->oauth_uid)->first();

            if ($account && $account->oauth_uid === $request->oauth_uid) {
                return true;
            } else {
                DB::beginTransaction();
                $new_account = new Accounts();
                $new_account->oauth_provider = $request->oauth_provider;
                $new_account->oauth_uid = $request->oauth_uid;
                $new_account->image = $request->image;
                $new_account->username = $request->username;
                // $new_account->gender = $request->gender;
                $new_account->email = $request->email;
                if($request->oauth_provider == 'Google' || $request->oauth_provider == 'Facebook'){
                    $new_account->password = null;
                }else{
                     $new_account->password = Hash::make($request->password);
                }
                // $new_account->phone = $request->phone;
                // $new_account->address = $request->address;

                $new_account->save();
                DB::commit();
                return true;
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return false;
        }
    }

    public function checkEmail($request)
    {

        if (Accounts::where('email', $request->email)->exists()) {
            return true;
        }
        return false;
    }

    public function forgotPassword($request)
    {
        if ($request->email) {
            $account = Accounts::where('email', $request->email)->first();
            $account->password = Hash::make($request->password);
            $account->save();
            return true;
        } else {
            return false;
        }

    }

    public function deleteAccount($request){
        $account = Accounts::where('email', $request->email)->first();
        if($account){
            $account->delete();
            return true;
        }else{
            return false;
        }
    }

}
