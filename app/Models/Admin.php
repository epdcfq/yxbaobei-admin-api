<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable; // 注意这里要和User模型一样


class Admin extends Model
{
    //
    protected $fillable = [
		'username', 'password', 'email'
	];
}
