<?php

namespace Database\Factories;

use App\Models\Pessoa;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HistoricoStatus>
 */
class HistoricoStatusFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition()
    {
        $count = count(Pessoa::all());
        return [
            'status' => Pessoa::status[random_int(0, 2)],
            'user_id' => random_int(1, $count),
            'pessoa_id' => random_int(1, $count),
        ];
    }
}
