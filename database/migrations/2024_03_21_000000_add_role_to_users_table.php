<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tidak perlu menambahkan kolom role karena sudah menggunakan spatie/laravel-permission
    }

    public function down()
    {
        // Tidak perlu rollback
    }
};
