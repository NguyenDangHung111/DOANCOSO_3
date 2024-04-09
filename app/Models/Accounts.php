<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticateTrait;

class Accounts extends Model implements Authenticatable
{
    use AuthenticateTrait;
    use HasFactory;
    protected $table = 'account'; // Tên bảng tương ứng với model

    protected $fillable = [
        'oauth_provider',
        'oauth_uid',
        'image',
        'username',
        'gender',
        'email',
        'password',
        'phone',
        'address',
        'otp',
        'otp_created_at'
    ];

    protected $primaryKey = 'id'; // Khai báo khóa chính của model

    public $timestamps = true; // Laravel sẽ quản lý tự động các trường created_at và updated_at
}
