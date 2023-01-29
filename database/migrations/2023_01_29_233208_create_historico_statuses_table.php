<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_statuses', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->enum('status', \App\Models\Pessoa::status);

            $table->foreignId('user_id')->constrained();
            $table->foreignId('pessoa_id')->constrained();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('historico_statuses');
    }
};
