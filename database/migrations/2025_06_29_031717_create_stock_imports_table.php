<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockImportsTable extends Migration
{
    public function up()
    {
        Schema::create('stock_imports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('quantity');
            $table->date('import_date');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_imports');
    }
}