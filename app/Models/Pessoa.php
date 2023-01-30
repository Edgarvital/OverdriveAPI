<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pessoa extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'telefone',
        'documento',
        'user_id',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public const status = ['Pendente', 'Ativo', 'Inativo'];

    public static function messages()
    {
        return [
            '*.required' => 'O campo :attribute é obrigatorio!',
            '*.unique' => 'O campo :attribute deve ser único!'
        ];
    }
}
