<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MigrationLaravelProductModule extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasTable('product_categories')) {
            Schema::create('product_categories', function (Blueprint $table) {
                $table->increments('id');

                $table->integer('parent_id')->nullable();
                $table->integer('lft')->nullable();
                $table->integer('rgt')->nullable();
                $table->integer('depth')->nullable();

                $table->string('name');
                $table->string('crop_type')->default('square');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('product_brands')) {
            Schema::create('product_brands', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('product_showcases')) {
            Schema::create('product_showcases', function (Blueprint $table) {
                $table->increments('id');
                $table->string('name');
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('products')) {
            Schema::create('products', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('category_id')->unsigned();
                $table->foreign('category_id')->references('id')->on('product_categories')->onDelete('cascade');
                $table->integer('brand_id')->unsigned();
                $table->foreign('brand_id')->references('id')->on('product_brands')->onDelete('cascade');

                $table->string('name');
                $table->decimal('amount', 5, 2)->nullable();
                $table->string('code')->nullable();
                $table->integer('photo_id')->unsigned()->index();
                $table->longText('short_description')->nullable();
                $table->longText('description')->nullable();
                $table->string('meta_title')->nullable();
                $table->string('meta_description')->nullable();
                $table->string('meta_keywords')->nullable();

                $table->integer('read')->unsigned()->default(0);
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('product_descriptions')) {
            Schema::create('product_descriptions', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->string('title');
                $table->longText('description');
                $table->boolean('is_publish')->default(0);
                $table->timestamps();

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('product_product_showcase')) {
            Schema::create('product_product_showcase', function (Blueprint $table) {
                $table->integer('product_showcase_id')->unsigned()->index();
                $table->foreign('product_showcase_id')->references('id')->on('product_showcases')->onDelete('cascade');

                $table->integer('product_id')->unsigned()->index();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->unsignedInteger('order'); // ürün vitrin sıralaması

                $table->engine = 'InnoDB';
            });
        }

        if ( ! Schema::hasTable('product_photos')) {
            Schema::create('product_photos', function (Blueprint $table) {
                $table->increments('id');
                $table->integer('product_id')->unsigned();
                $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');

                $table->string('photo');

                $table->engine = 'InnoDB';
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
        Schema::drop('product_photos');
        Schema::drop('product_product_showcase');
        Schema::drop('product_descriptions');
        Schema::drop('products');
        Schema::drop('product_categories');
        Schema::drop('product_brands');
        Schema::drop('product_showcases');
    }
}
