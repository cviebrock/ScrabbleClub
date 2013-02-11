<?php

class News {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function($table) {
			$table->increments('id')->unsigned();
			$table->date('date');
			$table->string('title');
			$table->text('body');
			$table->integer('author_id')->unsigned();
			$table->string('fb_album');
			$table->boolean('active')->index();
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
		Schema::drop('news');
	}

}
