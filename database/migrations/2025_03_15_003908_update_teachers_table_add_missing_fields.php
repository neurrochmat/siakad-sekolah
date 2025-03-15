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
        Schema::table('teachers', function (Blueprint $table) {
            // Add new columns first
            $table->string('tempat_lahir')->nullable()->after('jenis_kelamin');
            $table->date('tanggal_lahir')->nullable()->after('tempat_lahir');
            $table->string('foto')->nullable()->after('telepon');
            $table->foreignId('school_id')->nullable()->after('user_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true)->after('school_id');

            // Drop email column karena sudah ada di users table
            $table->dropColumn('email');

            // Drop status column
            $table->dropColumn('status');

            // Rename nama to nama_lengkap
            $table->renameColumn('nama', 'nama_lengkap');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            // Restore original columns first
            $table->renameColumn('nama_lengkap', 'nama');
            $table->string('email')->unique();
            $table->enum('status', ['active', 'inactive'])->default('active');

            // Drop new columns
            $table->dropForeign(['school_id']);
            $table->dropColumn([
                'tempat_lahir',
                'tanggal_lahir',
                'foto',
                'school_id',
                'is_active'
            ]);
        });
    }
};
