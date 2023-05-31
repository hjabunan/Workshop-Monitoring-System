<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConfirmUnits extends Model
{
    use HasFactory;

    protected $table = 'confirm_units';
    protected $fillable = ['CUClass','CUStatus','CUBrand','CUBay','CUCode','CUModel','CUSerialNum','CUMastHeight','CURemarks','POUIDE'];
}
