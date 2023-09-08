<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('item', function (Blueprint $table) {
           $table->integer('harga_asli')->after('harga');
            $table->boolean('is_flash_sale')->default(false)->after('harga_asli');
        });
    }

    public function down()
    {
        Schema::table('item', function (Blueprint $table) {
            $table->dropColumn(['harga_asli', 'is_flash_sale']);
        });
    }
};
