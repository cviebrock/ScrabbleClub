<?php

class News extends BaseModel {

	public static $timestamps = true;

	public $rules = array(
		'title'     => 'required|max:128',
		'body'      => 'required',
		'date'      => 'required|date',
		'author_id' => 'required|exists:players,id',
		'fb_album'	=> 'numeric',
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
		if ( paragraphs($this->body, 2, '') == $this->body ) {
			$link = '';
		} else {
			$link = '<p>'.HTML::link_to_route('news_item', 'Read more...', array($this->id, $this->slug)).'</p>';
		}

		return $this->summary . $link;

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
		require_once path('bundle').'docs/libraries/markdown.php';
		return Markdown($text);
	}

	public function album($reload=false)
	{

		if ($reload) {
			Cache::forget('fb.album.'.$this->fb_album);
		}

		return Cache::remember('fb.album.'.$this->fb_album, function() {

			if (!$this->fb_album) return;

			$fb = Ioc::resolve('facebook');
			$temp = $fb->api('/'.$this->fb_album, 'GET', array(
				'fields' => 'photos.fields(id,name,picture,source,link,images),link,likes.fields(link)'
			));

			$r = '';

			if (
				array_key_exists('photos', $temp) &&
				is_array($temp['photos']) &&
				array_key_exists('data', $temp['photos']) &&
				is_array($temp['photos']['data'])
			) {

				$r = <<< EOB
<div class="news-album">
	<h2>Photos</h2>
	<p>
		Click each photo for a larger version, or
		<a href="{$temp['link']}" target="_blank">click here</a>
		to view this album on Facebook.
	</p>
	<ul>
EOB;

				foreach ($temp['photos']['data'] as $photo) {

					$r .= sprintf('<li><a href="%s" class="fancybox" title="%s" rel="tooltip"><img src="%s" alt="%2$s"></a></li>',
						$photo['source'],
						(array_key_exists('name', $photo)
							? preg_replace( '/\n/', '<br>', HTML::entities($photo['name']) )
							: ''
						),
						$photo['picture']
						) . "\n";
				}

				$r .= "</ul></div>";

			}

			return $r;

		}, 0 );

	}


}
