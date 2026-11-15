<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Contoh: Zona 1 - Surabaya & Sidoarjo
            $table->string('cities'); // Contoh: Surabaya, Sidoarjo, Gresik
            $table->unsignedInteger('rate_per_kg'); // Tarif dalam Rupiah
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 20)->unique(); // KC + YYYYMMDD + 6 digit
            $table->string('sender_name');
            $table->string('receiver_name');
            $table->text('destination_address');
            $table->foreignId('zone_id')->constrained('zones')->cascadeOnDelete();
            $table->decimal('actual_weight', 8, 2);
            $table->decimal('length', 8, 2)->default(0);
            $table->decimal('width', 8, 2)->default(0);
            $table->decimal('height', 8, 2)->default(0);
            $table->decimal('volumetric_weight', 8, 2)->default(0);
            $table->unsignedInteger('chargeable_weight');
            $table->unsignedInteger('total_cost');
            $table->enum('current_status', [
                'DIBUAT',
                'DIJEMPUT',
                'TRANSIT',
                'DIANTAR',
                'DITERIMA'
            ])->default('DIBUAT');
            $table->timestamps();
        });

        Schema::create('shipment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->string('status', 20);
            $table->string('location')->nullable();
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('shipment_histories');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('zones');
    }
};