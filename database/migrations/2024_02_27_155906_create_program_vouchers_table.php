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
        Schema::create('program_vouchers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('program_id')->nullable();
            $table->bigInteger('campaign_id')->nullable();
            $table->string('code')->nullable();
            $table->string('receipt_no')->nullable();
            $table->bigInteger('generated_voucher_id')->nullable();
            $table->bigInteger('points')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('program_vouchers');
    }
};
