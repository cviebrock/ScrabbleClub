<?php

class Validator extends Laravel\Validator {


	public function validate_date($attribute, $value, $parameters)
	{
		try {
			$date = new DateTime($value);
			return $date->format('Y-m-d') == $value;
		} catch ( Exception $e ) {
			return false;
		}

	}


}