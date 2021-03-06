<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStorageTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('storage', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('domain')->unsigned()->index();
			$table->foreign('domain')->references('id')->on('domains');
			$table->integer('size');
			$table->integer('files');
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
		Schema::drop('storage');
	}

}
