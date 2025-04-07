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
        Schema::create('external_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('external_id');
            $table->string('title');
            $table->text('description');
            $table->string('link');
            $table->timestamps();

            $table->unique('external_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('external_jobs');
    }
};
