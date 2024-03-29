<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model
{
    use HasFactory;

    protected $table = "wms_activity_logs";

    public function userDetails() {
        return $this->belongsTo(User::class, 'user_id');
    }
}
