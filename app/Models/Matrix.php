<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Matrix extends Model
{
    use HasFactory;

    // matricesがusersの外部キー所持
    public function users() {
        return $this->belongTo(User::calss);
    }

    // factorsがmatricesの外部キー所持
    public function factors() {
        return $this->hasMany(Factor::calss);
    }
}
