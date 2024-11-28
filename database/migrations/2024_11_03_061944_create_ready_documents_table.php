<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use phpDocumentor\Reflection\Types\Nullable;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('ready_documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('petition_id');
            $table->string('name');
            $table->string('path');
            $table->string('note')->nullable();
            $table->string('electronKey')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ready_documents');
    }
};
