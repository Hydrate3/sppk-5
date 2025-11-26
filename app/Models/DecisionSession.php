<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DecisionSession extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function criteria()
    {
        return $this->hasMany(Criterion::class, 'session_id');
    }

    public function alternatives()
    {
        return $this->hasMany(Alternative::class, 'session_id');
    }
}

