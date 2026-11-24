<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('zones', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('cities');
            $table->unsignedInteger('rate_per_kg');
            $table->timestamps();
        });

        Schema::create('shipments', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 16)->unique();
            $table->string('sender_name', 100);
            $table->string('sender_phone', 20);
            $table->text('sender_address');
            $table->string('receiver_name', 100);
            $table->string('receiver_phone', 20);
            $table->text('receiver_address');
            $table->foreignId('zone_id')->constrained('zones')->restrictOnDelete();
            $table->decimal('actual_weight', 8, 2);
            $table->decimal('length', 8, 2);
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->decimal('volumetric_weight', 8, 2);
            $table->unsignedInteger('chargeable_weight');
            $table->unsignedInteger('total_cost');
            $table->string('current_status', 20)->default('DIBUAT')->index();
            $table->timestamps();

            $table->index('created_at');
        });

        Schema::create('shipment_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shipment_id')->constrained('shipments')->cascadeOnDelete();
            $table->string('status', 20);
            $table->foreignId('courier_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('notes', 255)->nullable();
            $table->timestamps();

            $table->index(['shipment_id', 'created_at']);
        });

        Schema::create('tracking_sequences', function (Blueprint $table) {
            $table->date('date')->primary();
            $table->unsignedInteger('last_number')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tracking_sequences');
        Schema::dropIfExists('shipment_histories');
        Schema::dropIfExists('shipments');
        Schema::dropIfExists('zones');
    }
};
