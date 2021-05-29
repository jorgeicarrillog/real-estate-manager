<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePropertiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organization_id');
            $table->unsignedBigInteger('owner_id');
            $table->unsignedBigInteger('properties_type_id');
            $table->unsignedBigInteger('citie_id');
            $table->string('title');
            $table->text('description');
            $table->string('address')->nullable();
            $table->float('longitude')->nullable();
            $table->float('latitude')->nullable();
            $table->float('area')->nullable();
            $table->integer('bedrooms');
            $table->integer('toilets');
            $table->integer('value');
            $table->integer('floor')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('organization_id')->references('id')->on('organizations');
            $table->foreign('owner_id')->references('id')->on('owners');
            $table->foreign('properties_type_id')->references('id')->on('properties_types');
            $table->foreign('citie_id')->references('id')->on('cities');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('properties');
    }
}
