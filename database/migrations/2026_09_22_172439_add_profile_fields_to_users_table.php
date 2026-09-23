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
            $table->string('full_name')->nullable()->after('name');
            $table->string('phone')->nullable()->after('email');
            $table->string('role')->default('Kasir')->after('phone');
            $table->string('shift')->default('1')->after('role');
            $table->string('outlet_name')->default('Kafe Ridho')->after('shift');
            $table->string('outlet_address')->default('Jl.way huwi no 5 lampung')->after('outlet_name');
            $table->string('work_hours')->default('08:00 - 16:00 (Shift 1)')->after('outlet_address');
            $table->string('avatar')->nullable()->after('work_hours');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'full_name',
                'phone',
                'role',
                'shift',
                'outlet_name',
                'outlet_address',
                'work_hours',
                'avatar',
                'last_login_at',
            ]);
        });
    }
};
