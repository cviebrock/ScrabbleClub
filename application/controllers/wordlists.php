<?php

class Wordlists_Controller extends Base_Controller {

	const STEM67 = array(
		'tisane', 'retina', 'satire', 'arsine', 'senior'
  );

	public function get_index()
	{
		$this->layout->with('title', 'Word Lists')
      ->with('canonical','wordlists')
			->nest('content', 'wordlists.index', array(
        'stem67' => self::STEM67
      ));
	}

	public function get_stem($stem)
	{
		$stem = Str::upper($stem);
		$words = array();

		foreach(range('A','Z') as $letter)
		{
			$alphagram = alphabetize_word($stem.$letter);
			$words[$letter] = ValidWord::where('alphagram','=',$alphagram)->order_by('word','asc')->get();
		}

		$this->layout->with('title', 'Stem List - '.$stem.'+?')
        ->with('canonical', 'wordlists/'.Str::lower($stem))
			->nest('content', 'wordlists.stem', compact('stem','words'));
	}


}
