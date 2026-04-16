<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('email');
            $table->string('company')->nullable()->after('phone');
            $table->string('company_address')->nullable()->after('company');
            $table->string('avatar')->nullable()->after('company_address');
            $table->enum('status', ['active', 'suspended', 'inactive'])->default('active')->after('avatar');
            $table->text('notes')->nullable()->after('status');
            $table->timestamp('last_login_at')->nullable();
        });
    }
    public function down(): void {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone','company','company_address','avatar','status','notes','last_login_at']);
        });
    }
};
