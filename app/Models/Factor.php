<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Factor extends Model
{
    use HasFactory;

    // factorsがmatricesの外部キー所持
    public function matrices() {
        return $this->belongTo(Matrix::calss);
    }

    // candidateがfactorsの外部キー所持
    public function candidates() {
        return $this->hasMany(Candidate::calss);
    }
}
