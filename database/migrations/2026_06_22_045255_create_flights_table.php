<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('flights', function (Blueprint $table) {
            $table->id();

            $table->string('flight_number', 20)->unique();

            $table->foreignId('airline_id')
                ->constrained('airlines')
                ->cascadeOnDelete();

            $table->foreignId('origin_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->foreignId('destination_id')
                ->constrained('cities')
                ->cascadeOnDelete();

            $table->dateTime('departure');
            $table->dateTime('arrival');

            $table->decimal('price', 10, 2);

            $table->unsignedInteger('seats_available')->default(0);

            $table->string('aircraft_type', 100)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('flights');
    }
};
