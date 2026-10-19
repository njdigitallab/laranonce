<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use NjDigitalLab\Laranonce\Models\Nonce;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(new Nonce()->getTable(), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('salt');
            $table->string('uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(new Nonce()->getTable());
    }
};
