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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->longText('description')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('company_id')->nullable();
            $table->string('status')->nullable();
            $table->bigInteger('vouchers_quantity')->nullable();
            $table->bigInteger('total_cost')->nullable();
            $table->string('reward_name')->nullable();
            $table->bigInteger('reward_points')->nullable();
            $table->bigInteger('points_per_voucher')->nullable();
            $table->bigInteger('price_of_voucher')->nullable();
            $table->string('company_name')->nullable();
            $table->string('company_phone')->nullable();
            $table->string('company_address')->nullable();
            $table->string('company_email')->nullable();
            $table->string('company_website')->nullable();
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
