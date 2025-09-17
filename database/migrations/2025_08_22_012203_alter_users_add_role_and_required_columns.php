<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Bổ sung/đảm bảo các cột tồn tại
            if (!Schema::hasColumn('users', 'name')) {
                $table->string('name')->after('id');
            }
            if (!Schema::hasColumn('users', 'email')) {
                $table->string('email')->unique()->after('name');
            }
            if (!Schema::hasColumn('users', 'password')) {
                $table->string('password')->after('email');
            }
            if (!Schema::hasColumn('users', 'role')) {
                $table->string('role')->default('customer')->after('password'); // 'admin' | 'customer'
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'role')) {
                $table->dropColumn('role');
            }
            // Không xóa name/email/password để tránh mất dữ liệu
        });
    }
};
