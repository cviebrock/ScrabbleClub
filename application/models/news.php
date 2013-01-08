<?php

class News extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'     => 'required|max:128',
		'body'      => 'required',
		'date'      => 'required|date',
		'author_id' => 'required|exists:players,id',
	);

	public function author()
	{
	 return $this->belongs_to('Player', 'author_id');
	}


	public function get_full_article()
	{
		return $this->markdown($this->body);
	}


	public function get_summary()
	{
		$text = paragraphs($this->body, 2, '');
		return $this->markdown($text);
	}

	public function get_summary_with_link()
	{
		$text = paragraphs($this->body, 2, '');

		return $this->markdown($text) .
			'<p>'.HTML::link_to_route('news_item', 'Read more...', array($this->id, $this->slug)).'</p>';
	}

	public function get_slug()
	{
		return Str::slug($this->title);
	}


	public function get_formatted_date()
	{
		return format_date($this->date);
	}


	protected function markdown($text)
	{
		require path('bundle').'docs/libraries/markdown.php';
		return Markdown($text);
	}


}
