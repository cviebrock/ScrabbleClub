<?php

class ValidWord extends BaseModel {
	public static $timestamps = false;


	public function __toString()
	{
		return $this->word;
	}

}