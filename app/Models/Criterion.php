<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Criterion extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'type',
        'weight',
    ];

    public function session()
    {
        return $this->belongsTo(DecisionSession::class, 'session_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'criterion_id');
    }
}
