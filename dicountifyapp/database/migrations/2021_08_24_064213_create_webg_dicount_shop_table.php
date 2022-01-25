<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWebgDicountShopTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('webg_dicount_shop', function (Blueprint $table) {
            $table->id();
            $table->float('max_discount_percentage', 2, 2);
            $table->integer('discount_step');
            $table->integer('countdown_duration');
            $table->integer('widget_position');
            $table->enum('is_active', ['yes', 'no']);
            $table->string('shop_id');
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
        Schema::dropIfExists('webg_dicount_shop');
    }
}
