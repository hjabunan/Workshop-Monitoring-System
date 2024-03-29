<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BayArea extends Model
{
    use HasFactory;

    protected $fillable = ['name','section','category','status'];

    protected $table = 'wms_bay_areas';
}
