<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    use HasFactory;

    // candidatesがfactorsの外部キー所持
    public function users() {
        return $this->belongTo(Factor::calss);
    }

}
