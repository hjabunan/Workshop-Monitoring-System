<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UnitParts extends Model
{
    use HasFactory;

    public function part(){
        return $this->belongsTo(Parts::class, 'PIPartID');
    }

    protected $table = "unit_parts";
}
