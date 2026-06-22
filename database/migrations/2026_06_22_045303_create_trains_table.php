<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trains', function (Blueprint $table) {
            $table->id();

            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('type', 50);
            $table->string('iata_code', 50)->unique();

            $table->foreignId('origin_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->foreignId('destination_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->time('departure_time');
            $table->time('arrival_time');

            $table->decimal('base_price', 10, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trains');
    }
};
