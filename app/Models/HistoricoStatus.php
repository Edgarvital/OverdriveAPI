<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoricoStatus extends Model
{
    use HasFactory;

    protected $fillable = [
        'pessoa_id',
        'status',
        'user_id'
    ];

    public function pessoa()
    {
        return $this->belongsTo(Pessoa::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
