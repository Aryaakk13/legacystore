<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->decimal('price', 12, 2);
            $table->enum('category', ['rank', 'item', 'crate', 'key', 'other'])->default('other');
            $table->integer('stock')->default(-1); // -1 = unlimited
            $table->string('image_url')->nullable();
            $table->boolean('is_active')->default(true);
            $table->json('features')->nullable();
            $table->json('commands')->nullable();
            $table->integer('discount_percent')->default(0);
            $table->timestamp('sale_ends_at')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();

            $table->index('category');
            $table->index('is_active');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}

