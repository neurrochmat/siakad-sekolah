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
        Schema::table('students', function (Blueprint $table) {
            // Rename column nama to nama_lengkap
            $table->renameColumn('nama', 'nama_lengkap');

            // Add new columns
            $table->string('nisn')->after('nis')->unique();
            $table->string('tempat_lahir')->after('jenis_kelamin');
            $table->date('tanggal_lahir')->after('tempat_lahir');
            $table->string('nama_ayah')->after('alamat')->nullable();
            $table->string('nama_ibu')->after('nama_ayah')->nullable();
            $table->string('telepon_ortu')->after('nama_ibu')->nullable();
            $table->string('foto')->after('telepon_ortu')->nullable();
            $table->foreignId('school_id')->after('class_id')->constrained();
            $table->boolean('is_active')->after('school_id')->default(true);

            // Drop unused columns
            $table->dropColumn(['telepon', 'email', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            // Restore original columns
            $table->string('telepon')->nullable();
            $table->string('email');
            $table->boolean('status')->default(true);

            // Drop new columns
            $table->dropColumn([
                'nisn',
                'tempat_lahir',
                'tanggal_lahir',
                'nama_ayah',
                'nama_ibu',
                'telepon_ortu',
                'foto',
                'school_id',
                'is_active'
            ]);

            // Rename column back
            $table->renameColumn('nama_lengkap', 'nama');
        });
    }
};
