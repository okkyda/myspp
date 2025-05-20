<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Transaction extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function user() {
        return $this->belongsTo(User::class,'user_id');
    }

    public function departments() {
        return $this->belongsTo(Departmen::class,'departments_id');
    }

}
