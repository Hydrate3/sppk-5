<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alternative extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
    ];

    public function session()
    {
        return $this->belongsTo(DecisionSession::class, 'session_id');
    }

    public function evaluations()
    {
        return $this->hasMany(Evaluation::class, 'alternative_id');
    }
}
    