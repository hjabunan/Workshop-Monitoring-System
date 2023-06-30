<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AreaTable extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'top', 'left'];
}
