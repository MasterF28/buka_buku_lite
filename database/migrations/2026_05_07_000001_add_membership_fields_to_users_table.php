<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('membership_type')->default('standar')->after('role');
            $table->unsignedSmallInteger('premium_package')->nullable()->after('membership_type');
            $table->dateTime('premium_start_date')->nullable()->after('premium_package');
            $table->dateTime('premium_expired_date')->nullable()->after('premium_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'membership_type',
                'premium_package',
                'premium_start_date',
                'premium_expired_date',
            ]);
        });
    }
};

