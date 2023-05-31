<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BTReport extends Model
{
    use HasFactory;

    protected $fillable = ['reportID','brandz','unitstatus','bayarea','ucode','umodel','userial','uheight','remarks'];
}
