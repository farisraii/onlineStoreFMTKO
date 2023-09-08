<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFlashSaleColumnsToItemPesananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('item_pesanan', function (Blueprint $table) {
            // Tambahkan kolom harga_asli dan diskon
            $table->integer('harga_asli');
            $table->integer('diskon')->default(0); // Default diskon adalah 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('item_pesanan', function (Blueprint $table) {
            // Hapus kolom harga_asli dan diskon jika perlu
            $table->dropColumn('harga_asli');
            $table->dropColumn('diskon');
        });
    }
}
