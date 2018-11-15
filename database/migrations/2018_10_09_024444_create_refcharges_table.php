<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRefchargesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('b_refcharges', function (Blueprint $table) {
            $table->increments('charge_id');
            $table->string('charge_code')->default('');
            $table->string('charge_desc')->default('');
            $table->datetime('created_datetime')->nullable(true);
            $table->integer('created_by')->nullable(true);
            $table->datetime('modified_datetime')->nullable(true);
            $table->integer('modified_by')->nullable(true);
            $table->datetime('deleted_datetime')->nullable(true);
            $table->integer('deleted_by')->nullable(true);
            $table->tinyInteger('is_deleted', false, '0')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('refcharges');
    }
}
