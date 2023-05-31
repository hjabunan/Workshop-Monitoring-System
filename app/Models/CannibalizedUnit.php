<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CannibalizedUnit extends Model
{
    use HasFactory;

    protected $fillable = ['cuCONum','cuStatus','cuDate','cuCFModelNum', 'cuCFSerialNum', 'cuCFRentalCode', 'cuCFSection', 'cuCFPIC',
    'cuCFPrepBy', 'cuCFPrepDate', 'cuCFStartTime', 'cuCFEndTime', 'cuITModelNum', 'cuITSerialNum', 'cuITRentalCode', 'cuITCustomer',
    'cuITCustAddress', 'cuITCustArea', 'cuITSupMRI', 'cuITSupSTO', 'cuITRecBy', 'cuCPrepBy', 'cuRPRetBy', 'cuRPDate', 'cuRRecBy',
    'cuDocRefNum', 'cuPartNum', 'cuDescription1', 'cuQuantity1', 'cuRemarks1'];
}
