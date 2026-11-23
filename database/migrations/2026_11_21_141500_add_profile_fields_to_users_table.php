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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->default('assets/images/admin-khanh.jpg');
            }
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->default('0900 123 456');
            }
            if (!Schema::hasColumn('users', 'role_title')) {
                $table->string('role_title')->nullable()->default('Founder & Ban Quản trị');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'phone', 'role_title', 'bio']);
        });
    }
};
