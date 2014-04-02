<?php

class Alphagram {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('validwords', function($table) {
			$table->string('alphagram')->index();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('validwords', function($table) {
			$table->drop_column('alphagram');
		});
	}

}