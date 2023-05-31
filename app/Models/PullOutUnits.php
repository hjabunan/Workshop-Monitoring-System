<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PullOutUnits extends Model
{
    // use HasFactory;

    protected $table = 'pull_out_units';
    protected $fillable = ['POUClass','POUDate','POUCustomer','POUCustAdd','POUCode','POUModel','POUSerialNum','POUMastHeight','POURemarks','POUTransferTo','POUnitStatus'];
}
