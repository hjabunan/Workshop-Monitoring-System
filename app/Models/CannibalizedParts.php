<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CannibalizedParts extends Model
{
    use HasFactory;

    protected $fillable = ['cuCONum','cuDate', 'cuPartNum1', 'cuDescription1', 'cuQuantity1', 'cuRemarks'];
}
