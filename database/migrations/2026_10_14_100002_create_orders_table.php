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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            // Identitas & Akses Guest
            $table->string('order_number')->unique(); // Format: PET-YYYYMMDD-XXXX
            $table->uuid('tracking_token')->unique(); // UUID publik invoice

            // Data Pembeli
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->text('shipping_address');
            $table->string('city_or_district');
            $table->string('postal_code');

            // Data Logistik (Siap Integrasi)
            $table->unsignedInteger('total_weight_grams');
            $table->string('courier_code'); // jne, jnt, sicepat, instant
            $table->string('courier_service'); // REG, YES, SameDay, dll
            $table->unsignedBigInteger('shipping_cost');
            $table->string('tracking_number')->nullable();

            // Data Finansial & Pembayaran (Siap Integrasi)
            $table->unsignedBigInteger('subtotal');
            $table->unsignedBigInteger('grand_total');
            $table->string('payment_method')->default('mock_gateway'); // mock_gateway, bank_transfer
            $table->enum('payment_status', ['unpaid', 'paid', 'failed', 'expired'])->default('unpaid');
            $table->string('payment_reference')->nullable();
            $table->timestamp('paid_at')->nullable();

            // Status Pesanan
            $table->enum('order_status', ['pending', 'processing', 'shipped', 'completed', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
