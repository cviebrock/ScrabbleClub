<?php

class App {


	public static function format_date($date, $format='d-M-Y') {
		if ($date) {
			if (!($date instanceof DateTime)) {
				try {
					$date = new DateTime($date);
				} catch ( Exception $e ) {
					return null;
				}
			}
			return $date->format($format);
		} else {
			return null;
		}
	}


	public static function has_errors($object, $field)
	{
		return array_key_exists($field, $object->errors);
	}


	public static function errors_for($object, $field, $inline=true)
	{
		if (array_key_exists($field, $object->errors)) {
			return View::make('partials.form_errors')
					->with('messages', $object->errors[$field] )
					->with('inline' , $inline);
		} else {
			return '';
		}
	}


	public static function help_for($object, $field)
	{
		if (array_key_exists($field, $object->help)) {
			return View::make('partials.form_help')
					->with('message', $object->help[$field] );
		} else {
			return '';
		}
	}


	public static function all_players()
	{
		$players = Player::all();
		$data = array(0=>'');
		foreach($players as $player) {
			$data[ $player->id ] = $player->fullname();
		}
		return $data;
	}

	public static function rwords($num=3, $with_score=true) {

		$words = array(
			'secretly','blithers','rigorism','updarted','airproof','misspelt','mesotron','stonable','pronging','rebutted',
			'premolds','vibrioid','pupilary','knollers','planular','trappose','sleekier','hyperope','urodeles','misunion',
			'liberate','corrades','entryway','literacy','intombed','enuresis','varietal','prefects','thrivers','feastful',
			'misdoing','advisory','erogenic','meltages','groupoid','pitfalls','bafflers','chromize','pureeing','cordwood',
			'tentlike','drizzled','submenus','unhanged','trochili','rezeroes','tweenies','folkways','legacies','palterer',
			'shealing','retracer','suffered','autocrat','employee','wagtails','absorber','combings','tepefied','cubistic',
			'grieving','morelles','ammoniac','sculptor','ungently','sealifts','awlworts','airbuses','bargeman','parsleys',
			'neuromas','assented','currency','statuses','begotten','vainness','coupling','wheezier','sonobuoy','fistnote',
			'samovars','ectozoon','mirepoix','username','reinjury','fascicle','unwitted','unyoking','homelike','slowdown',
			'frenches','trolands','applause','martlets','trickled','economic','rockiest','pennames','question','cheesing',
			'commoner','greenery','sherbets','bestowal','santeras','teethers','tickings','womaning','molybdic','mungoose',
			'braunite','riftless','updating','invocate','toddling','kindling','grayness','cullions','anchusas','debagged',
			'frothier','dragomen','prickled','calpains','knottier','crooning','shrieved','enisling','overurge','hounders',
			'madronas','hackable','crudding','unpurged','purblind','ejective','crankily','reactant','evermore','thorites',
			'overpays','sawteeth','sinciput','neurular','politics','resaddle','faddists','typifier','chelator','hypogene',
			'misspace','greasers','idealize','listable','flappier','sithence','inputted','temperas','cauldron','nervures',
			'succubas','pretrial','gadflies','scenario','doddered','amphipod','xeroxing','satiated','minutiae','capsuled',
			'sinusoid','ungraded','bullpout','printing','valuates','streeker','saboteur','hematine','complins','moseying',
			'garaging','sandfish','soochong','hyacinth','romaunts','melanist','unframed','creepage','homogony','squarish',
			'fairlead','seemings','sprinted','feminise','appellor','hoydened','tutoyers','resorter','polypeds','ringdove',
			'nonblack','epoxides','acrotism','orreries','barrator','foulness','ovoidals','letdowns','publican','fuckoffs',
			'smaltine','stampers','degrader','teratism','unwaning','farcical','overhard','tallisim','cabbalah','respaces',
			'sordidly','gleeking','aplastic','upswings','dianthus','oogamies','ratifier','quackish','burblers','hotelier',
			'analemma','truthful','capelins','guesting','hatchway','sheathed','solarism','teniases','invading','halluces',
			'bevelled','organums','antedate','talkiest','ribworts','shunpike','shrewdie','buxomest','quetzals','cipolins',
			'pastness','squatted','outburst','mucidity','believer','subagent','cabotage','butyrate','pyrolyze','preppily',
			'shoptalk','weirdoes','inclines','reassume','isopodan','noisiest','sectored','outpours','performs','cultches',
			'pilosity','outhauls','moveless','vegetate','fervency','popelike','unnerves','slickest','smooched','squeaker',
			'trifling','dawdling','frayings','meditate','lanciers','tornados','aglimmer','unrooted','dottiest','spookish',
			'hotheads','fastball','praisers','sinfonie','ingrates','swizzles','mendable','cadaster','handlike','enwheels',
			'paranoea','remanent','fameless','isolines','relearnt','zorillas','crankpin','arrogate','benomyls','pilsener',
			'faithing','bistoury','outmodes','throning','rindless','eversion','adulates','foregone','inflated','desulfur',
			'epitases','underfed','bubaline','exsecant','sodomist','shortias','baptisms','flutters','symphony','infarcts',
			'maintain','shippens','rarefied','flagrant','brassily','cambiums','oversoon','tangling','defunded','benefice',
			'chigetai','bedights','beclasps','waggling','dysurias','voidness','strawier','drowners','twirlier','tankards',
			'nattiest','firebomb','caressed','spumones','roseroot','extrados','swankier','mistypes','amphorae','paganize',
			'milepost','hockshop','forkedly','nudeness','fatbirds','livening','limberer','progress','milesimo','proofing',
			'tearless','confined','subtlety','hematoid','quintets','ashcakes','offshoot','turquois','blastula','lysogens',
			'moshavim','thundery','chording','octuplet','danegelt','anthemed','rattoons','subtheme','lynching','nonelite',
			'quantile','coumarou','shudders','cutovers','impolite','mercuric','firewood','aluminum','vicenary','amoretti',
			'truffles','oilproof','partitas','apostils','secretin','macrames','flapping','killjoys','imbrutes','porkwood',
			'joggling','regilded','chuggers','convenor','nostrils','malemute','foresaid','detacher','uncrated','drencher',
			'vanishes','jeremiad','diazepam','anaphase','extended','supertax','soarings','asterias','warrigal','shirkers',
			'pisiform','libretto','clevises','virtuose','shavings','wettings','sashless','pharmacy','writhing','dandyism',
			'bespoken','stenoses','cabalism','downlike','splendor','bowfront','workshop','unionism','ocarinas','chilling',
			'absolves','mongered','coholder','mainline','waterlog','aduncate','inkwoods','sartorii','shucking','sergeant',
			'promiser','doweling','unionise','cookable','matzoons','dizziest','ceramals','broadest','carcanet','contests',
			'staggart','pudibund','editable','okeydoke','forehead','shiplaps','roomiest','slithers','thorough','defecate',
			'conchies','beholden','funnyman','speeders','hebetude','perspiry','presoaks','sweetens','pantalet','pickoffs',
			'marauder','retinula','resculpt','chestful','saltings','uncrates','divorces','plaguers','goatskin','guaranty'
		);

		$return = '';

		while($num--) {
			if ($return) $return .= ', ';

			$return .= $words[ mt_rand(0,count($words)-1) ];

			if ($with_score) {
				$return .= ' ' . mt_rand(60,100);
			}

		}

		return $return;

	}

	public static function action_link_to_route($name, $title, $parameters=array(), $icon=null, $attributes = array() ) {

		$small = false;

		if ($icon) {
			$icons = explode('|',$icon);
			$tag = '<i class="';
			foreach($icons as $i) {
				if ($i=='small') {
					$small = true;
				} else {
					$tag .= 'icon-'.$i.' ';
				}
			}
			$tag .= '"></i>';
			$title = $tag.$title;
		}

		if (!array_key_exists('class', $attributes)) {
			$attributes['class'] = 'btn';
			if ($small) {
				$attributes['class'] .= ' btn-small';
			}
		}

		return static::link_to_route($name, $title, $parameters, $attributes );

	}


	public static function link_to_route($name, $title, $parameters = array(), $attributes = array(), $https = false)
	{
		return static::link(URL::to_route($name, $parameters, $https), $title, $attributes);
	}


	public static function link($url, $title, $attributes = array(), $https = false)
	{
		$url = HTML::entities(URL::to($url, $https));
		return '<a href="'.$url.'"'.HTML::attributes($attributes).'>'.$title.'</a>';
	}


}