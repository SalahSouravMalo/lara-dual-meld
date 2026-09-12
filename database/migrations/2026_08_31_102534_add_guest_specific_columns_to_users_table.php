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
            $table->after('google_id', function (Blueprint $table) {
                $table->boolean('is_guest_account')
                    ->default(false)
                    ->index();
                $table->string('guest_lease_token')->nullable();
                $table->timestamp('guest_lease_expires_at')
                    ->nullable()
                    ->index();
                $table->timestamp('last_activity_at')
                    ->nullable();
            });
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'is_guest_account',
                'guest_lease_token',
                'guest_lease_expires_at',
                'last_activity_at',
            ]);
        });
    }
};
