<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pessoa>
 */
class PessoaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => $this->faker->name,
            'telefone' => '('.random_int(00, 99) . ') ' . random_int(90000, 99999) . '-' . random_int(0000, 9999),
            'documento' => random_int(000, 999) . '.' . random_int(000, 999) . '.' . random_int(000, 999) . '-' . random_int(00,99),
            'status' => Pessoa::status[1]
        ];
    }
}
