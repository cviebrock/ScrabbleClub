<?php

class Wordlists_Controller extends Base_Controller {


	public function get_index()
	{

		$stem67 = array(
			'tisane', 'retina', 'satire', 'arsine', 'senior'
		);

		$this->layout->with('title', 'Word Lists')
			->nest('content', 'wordlists.index', compact('stem67'));
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
			->nest('content', 'wordlists.stem', compact('stem','words'));
	}


}
