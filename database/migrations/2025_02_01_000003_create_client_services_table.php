<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('client_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('service_id')->constrained()->cascadeOnDelete();
            $table->foreignId('package_id')->nullable()->constrained()->nullOnDelete();
            $table->string('label');
            $table->text('description')->nullable();
            $table->json('credentials')->nullable();
            $table->json('specs')->nullable();
            $table->enum('status', ['active','suspended','expired','cancelled','pending'])->default('pending');
            $table->enum('billing_cycle', ['monthly','quarterly','yearly','one-time'])->default('monthly');
            $table->decimal('price', 10, 2);
            $table->date('start_date');
            $table->date('next_renewal_date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('client_services'); }
};
