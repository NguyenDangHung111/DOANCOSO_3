<?php

namespace App\Services\Interfaces;

interface AccountServiceInterface
{
    public function get_accounts();
    public function create_account($request);
    public function checkEmail($request);
    public function forgotPassword($request);
    public function deleteAccount($request);
}
