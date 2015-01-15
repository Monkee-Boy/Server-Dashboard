<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->enum('what', array('bandwidth', 'storage')); // What changes
			$table->string('how'); // How it changes (increase, decrease, ...)
			$table->string('by'); // Number to measure
			$table->string('by_measure'); // What the number means (%, GB, ...)
			$table->string('where'); // Where to send notification
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('notifications');
	}

}
