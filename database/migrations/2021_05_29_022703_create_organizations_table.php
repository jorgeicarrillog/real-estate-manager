<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrganizationsTable extends Migration
{
    public function up()
    {
        Schema::create('organizations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('citie_id');
            $table->string('name', 100);
            $table->string('email', 50)->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('address', 150)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->string('photo_path', 100)->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('citie_id')->references('id')->on('cities');
        });
    }
}
