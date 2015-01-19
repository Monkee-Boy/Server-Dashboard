<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateBandwidthTableNewFields extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('bandwidth', function($table)
		{
			$table->dropColumn(array('logname', 'remote_user', 'request', 'size_response'));

			$table->string('method', 10)->after('time_taken');
			$table->text('file_name')->after('user_agent');
			$table->text('url')->after('file_name');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
