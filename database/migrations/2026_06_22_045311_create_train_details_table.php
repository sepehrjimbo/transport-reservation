<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('train_details', function (Blueprint $table) {
            $table->id();

            $table->foreignId('train_id')
                ->constrained('trains')
                ->cascadeOnDelete();

            $table->unsignedInteger('wagon_number');

            $table->enum('wagon_type', [
                'coupe',
                'economy',
                'business'
            ]);

            $table->unsignedInteger('capacity');

            $table->decimal('price_multiplier', 3, 2)->default(1.00);

            $table->string('description')->nullable();

            $table->timestamps();

            $table->unique(['train_id', 'wagon_number']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('train_wagons');
    }
};
