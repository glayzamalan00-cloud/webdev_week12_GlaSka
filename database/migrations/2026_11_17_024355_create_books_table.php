<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('author');
            $table->string('status')->default('to-read');
            $table->unsignedTinyInteger('rating')->nullable();
            $table->timestamps();
        });

        DB::statement("ALTER TABLE books ADD CONSTRAINT books_status_check CHECK (status IN ('to-read','reading','finished'))");
        DB::statement("ALTER TABLE books ADD CONSTRAINT books_rating_check CHECK (rating IS NULL OR rating BETWEEN 1 AND 5)");
    }

    public function down(): void
    {
        Schema::dropIfExists('books');
    }
};