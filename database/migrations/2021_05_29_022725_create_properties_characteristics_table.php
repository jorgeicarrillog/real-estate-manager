<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesCharacteristicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties_characteristics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        //table many to many
        Schema::create('propiertie_properties_characteristic', function (Blueprint $table) {
            $table->unsignedBigInteger('propertie_id');
            $table->unsignedBigInteger('properties_characteristic_id');
            $table->foreign('propertie_id')->references('id')->on('properties');
            $table->foreign('properties_characteristic_id')->references('id')->on('properties_characteristics')->name('properties_characteristics_fk');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('propiertie_properties_characteristic');
        Schema::dropIfExists('properties_characteristics');
    }
}
