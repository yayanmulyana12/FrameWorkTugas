<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        // Ubah default kolom role jadi 'admin' tanpa menambah kolom baru
        DB::statement("ALTER TABLE users MODIFY role ENUM('root','admin','user') DEFAULT 'admin'");
    }

    /**
     * Kembalikan perubahan migrasi.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan default role jadi 'user'
        DB::statement("ALTER TABLE users MODIFY role ENUM('root','admin','user') DEFAULT 'user'");
    }
};
