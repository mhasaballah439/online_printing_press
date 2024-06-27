<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id')->default(0);
            $table->integer('paper_size')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('tax')->default(0);
            $table->decimal('price',8,2)->default(0);
            $table->integer('first_page_color')->default(0);
            $table->string('paper_type')->nullable();
            $table->integer('aspects_printing')->default(0);
            $table->integer('packaging_id')->default(0);
            $table->integer('page_layout')->default(0);
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
        Schema::dropIfExists('carts');
    }
}
