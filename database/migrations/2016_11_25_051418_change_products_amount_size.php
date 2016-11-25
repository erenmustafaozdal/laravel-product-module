<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeProductsAmountSize extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('products','amount')) {
            Schema::table('products', function ($table) {
                $table->decimal('amount', 11, 2)->nullable()->change();
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
        if (Schema::hasColumn('products','amount')) {
            Schema::table('products', function ($table) {
                $table->decimal('amount', 5, 2)->nullable()->change();
            });
        }
    }
}
