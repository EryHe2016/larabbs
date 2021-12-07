<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPhoneCode extends Model
{
    use HasFactory;
    public $table = 'user_phone_codes';

    public $fillable = ['phone', 'code'];
}
