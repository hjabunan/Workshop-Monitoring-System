<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveredUnits extends Model
{
    // use HasFactory;

    protected $table = 'delivered_units';
    protected $fillable = ['DUID','CUID','POUID','DUClass','DUDate','DUCustomer','DUCustAdd','DUBrand','DUCode','DUModel','DUSerialNum','DUMastHeight','DURemarks'];
}
