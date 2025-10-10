<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            // Tambahkan kolom baru jika belum ada
            $table->string('product_name')->after('id');
            $table->string('unit')->after('product_name');
            $table->string('type')->after('unit');
            $table->string('information')->after('type');
            $table->integer('qty')->after('information');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            // Hapus kolom jika rollback
            $table->dropColumn(['product_name', 'unit', 'type', 'information', 'qty']);
        });
    }
};
