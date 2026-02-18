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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('description')->nullable();
            $table->string('cover_image')->nullable();
            $table->string('file_path');
            $table->boolean('is_login_required')->default(false);
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
            $table->boolean('is_active')->default(true); // Opsi untuk menyembunyikan e-book tanpa menghapus
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};
