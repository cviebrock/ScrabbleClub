<?php

class Alphagram_Task {


	public function run($arguments)
	{

		$limit = 20000;
		if (isset($arguments[0])) {
			$limit = $arguments[0];
		}

		$words = ValidWord::where('alphagram','=','')->take($limit);

		echo "Alphagramming $limit words:\n";
		ob_flush();
		$c = 0;

		foreach($words->get() as $word) {
			$word->alphagram = alphabetize_word($word->word);
			$word->save();
			$c++;
			if ($c % 1000 == 0) {
				echo $c . "\n";
				ob_flush();
			} else if ($c % 100 == 0) {
				echo '.';
				ob_flush();
			}
		}
		if ($c%100) echo $c . "\n";
		echo "Done.\n";

	}
}