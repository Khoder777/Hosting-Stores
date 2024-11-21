<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_sub_category_values', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('sub_category_property_id');
            $table->foreign('product_id')->references('id')->on('products');
            $table->string('image')->nullable();
            $table->foreign('sub_category_property_id')->references('id')->on('sub_categoey_properties');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sub_category_values');
    }
};
