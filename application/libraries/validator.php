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

	protected function validate_url_or_file($attribute, $value)
	{
		if (substr($value, 0,7)=='file://') {
			$temp = substr($value,7);
			$file = substr($temp,0,strpos($temp,'/'));
			return File::exists( path('uploads').$file );
		}
		return $this->validate_url($attribute, $value);

	}

}
