<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveBrandIdIndexOnProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function ($table) {
                $table->unsignedInteger('brand_id')->nullable()->change();
                $table->dropForeign(['brand_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('products')) {
            Schema::table('products', function ($table) {
                $table->integer('brand_id')->unsigned()->change();
                $table->foreign('brand_id')->references('id')->on('product_brands')->onDelete('cascade');
            });
        }
    }
}
