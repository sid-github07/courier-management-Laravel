<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCurrierProductInfosTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('currier_product_infos', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('currier_type');
            $table->integer('currier_quantity');
            $table->double('currier_fee', 8, 2);
            $table->text('currier_details')->nullable();
            $table->string('currier_code');

            $table->integer('currier_info_id')->unsigned()->index();

            $table->foreign('currier_info_id')
                    ->references('id')
                    ->on('currier_infos');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('currier_product_infos');
    }

}
