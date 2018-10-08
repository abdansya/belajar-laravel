<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipsToOrdersProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders_products', function (Blueprint $table) {
            $table->integer('product_id')->unsigned()->change();
            $table->foreign('product_id')->references('id')->on('products')
                ->onUpdate('cascade')->onDelete('restrict');
            
            $table->integer('order_id')->unsigned()->change();
            $table->foreign('order_id')->references('id')->on('orders')
                ->onUpdate('cascade')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders_products', function (Blueprint $table) {
            $table->dropForeign('orders_products_product_id_foreign');
            $table->dropForeign('orders_products_order_id_foreign');
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->dropIndex('orders_products_product_id_foreign');
            $table->dropIndex('orders_products_order_id_foreign');
        });

        Schema::table('orders_products', function (Blueprint $table) {
            $table->integer('product_id')->change();
            $table->integer('order_id')->change();
        });
    }
}
