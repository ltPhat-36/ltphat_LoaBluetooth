<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_new')->default(false);
            $table->boolean('is_featured')->default(false);
            $table->decimal('old_price', 10, 2)->nullable();
            $table->timestamp('sale_end')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_new', 'is_featured', 'old_price', 'sale_end']);
        });
    }
};
