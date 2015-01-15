<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBandwidthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('bandwidth', function(Blueprint $table)
		{
			$table->engine = 'InnoDB';
			$table->integer('domain')->unsigned()->index();
			$table->foreign('domain')->references('id')->on('domains');
			$table->string('ip', 30);
			$table->string('logname', 10);
			$table->string('remote_user', 10);
			$table->dateTime('request_time');
			$table->integer('time_taken');
			$table->string('request', 200);
			$table->integer('status')->unsigned();
			$table->integer('size_response')->unsigned();
			$table->integer('bytes_received')->unsigned();
			$table->integer('bytes_sent')->unsigned();
			$table->string('referer', 255);
			$table->string('user_agent', 255);
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
		Schema::drop('bandwidth');
	}

}
