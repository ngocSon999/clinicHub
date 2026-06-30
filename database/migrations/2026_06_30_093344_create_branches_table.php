<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('branches', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('code', 50)->unique();
            $table->string('phone', 20)->nullable();

            $table->string('province_id', 20)->nullable();
            $table->string('commune_id', 20)->nullable();
            $table->string('address_detail', 255)->nullable();
            $table->string('full_address', 255)->nullable();

            $table->tinyInteger('status')->default(1);
            $table->timestamps();

            $table->index(['province_id', 'commune_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branches');
    }
};
