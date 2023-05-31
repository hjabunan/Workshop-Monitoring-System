<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerArea extends Model
{
    use HasFactory;

    protected $fillable = ['name','status'];
}
