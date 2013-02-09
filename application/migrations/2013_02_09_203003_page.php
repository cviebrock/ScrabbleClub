<?php

class Page {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pages', function($table) {
			$table->increments('id')->unsigned();
			$table->string('url')->unique();
			$table->string('title');
			$table->text('body');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('pages');
	}

}
