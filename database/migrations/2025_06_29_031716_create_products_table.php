<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->integer('low_stock_threshold')->default(10);
            $table->date('last_import_date')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('products');
    }
}