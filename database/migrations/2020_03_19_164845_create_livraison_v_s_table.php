<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLivraisonVSTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('livraison_v_s', function (Blueprint $table) {
            $table->Increments('id');
            $table->String('numero')->nullable();
            $table->dateTime('date_livraison')->default(now());
            $table->integer('boutique_id')->unsigned()->nullable()->index();
            // $table->foreign('boutique_id')
            //     ->references('id')
            //     ->on('boutiques')
            //     ->onUpdate('cascade');
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
        Schema::dropIfExists('livraison_v_s');
    }
}
