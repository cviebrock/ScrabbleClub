<?php

class Private_Resources {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('resourcegroups', function($table) {
			$table->boolean('private')->default(0)->index();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('resourcegroups', function($table) {
			$table->drop_column('private');
		});
	}

}