<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TechnicianSchedule extends Model
{
    use HasFactory;
    
    protected $fillable = ['techname','scheddate','baynum','sow','activ','fromDate','toDate'];
}
