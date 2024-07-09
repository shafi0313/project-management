<?php

use App\Constants\IsActive;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name',100);
            $table->string('email', 64)->unique();
            $table->string('user_name', 32)->unique()->nullable();
            $table->unsignedTinyInteger('role')->default(0)->comment('1:Admin');
            $table->unsignedTinyInteger('gender')->nullable();
            $table->string('mobile', 32)->nullable();
            $table->string('address')->nullable();
            $table->string('image', 32)->nullable();
            $table->foreignId('section_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('sub_section_id')->nullable()->constrained()->onDelete('cascade');
            $table->boolean('is_active', [IsActive::ACTIVE, IsActive::INACTIVE])->default(IsActive::ACTIVE);
            $table->boolean('removable')->default(1);
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('cascade');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
