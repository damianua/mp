<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->unsignedInteger('parent_id')->nullable();
            $table->boolean('active')->default(1);
            $table->string('name', 255);
            $table->unsignedInteger('sort')->default(100);
            $table->tinyInteger('depth')->default(1);
            $table->unsignedInteger('left_margin')->default(0);
            $table->unsignedInteger('right_margin')->default(0);
            $table->string('external_id', 64);
            $table->timestamps();

            $table->foreign('parent_id')->references('id')->on('sections');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sections');
    }
}
