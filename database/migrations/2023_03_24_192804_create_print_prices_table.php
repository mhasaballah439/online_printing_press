<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePrintPricesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('print_prices', function (Blueprint $table) {
            $table->id();
            $table->integer('num_paper')->default(0);
            $table->integer('from_paper')->default(0);
            $table->integer('to_paper')->default(0);
            $table->integer('paper_size')->default(0);
            $table->integer('printing_type_id')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('print_prices');
    }
}
