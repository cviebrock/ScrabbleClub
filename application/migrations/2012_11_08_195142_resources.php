<?php

class Resources {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('resourcegroups', function($table) {
			$table->increments('id')->unsigned();
			$table->string('title');
			$table->integer('sort_order');
			$table->timestamps();
		});

		Schema::create('resources', function($table) {
			$table->increments('id')->unsigned();
			$table->integer('resourcegroup_id')->unsigned();
			$table->string('title');
			$table->text('description');
			$table->string('url');
			$table->boolean('active')->index();
			$table->integer('sort_order');
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('resources');
		Schema::drop('resourcegroups');
	}

}