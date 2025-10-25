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
        Schema::create('embeddings', function (Blueprint $table) {
            $table->id();
            $table->string('type'); // product/news
            $table->unsignedBigInteger('ref_id'); // ID của sản phẩm/news
            $table->json('vector'); // embedding
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('embeddings');
    }
};
