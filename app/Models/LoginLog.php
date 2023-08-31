<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LoginLog extends Model
{
    use HasFactory;

    protected $table = 'wms_login_logs';


    protected $fillable = [
        'login_username', 'login_time', 'login_ipaddress','login_eventtype','login_status','failure_reason'
    ];
}
