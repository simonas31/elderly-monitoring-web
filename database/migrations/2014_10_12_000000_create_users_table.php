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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('surname');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('security_type')->default(0); //0-none; 1-email; 2-SMS
            $table->integer('parent_user_id')->nullable()->default(null);
            $table->integer('role_id');
            $table->string('phone_number');
            $table->date('date_of_birth');
            $table->boolean('fall_notifications')->default(true);
            $table->mediumText('profile_picture')->nullable()->default(null);
            $table->timestamp('email_verified_at')->nullable();
            $table->string('two_factor_code')->nullable();
            $table->dateTime('two_factor_expires_at')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
