<?php

class BaseModel extends Eloquent {

	public $rules = array();
	public $messages = array();

	public $errors = array();

	public $help = array();


	public function error($field, $wrap=null)
	{
		if (array_key_exists($field, $this->errors)) {
			$e = current($this->errors[$field]);
			if ($wrap) {
				return "<$wrap>" . $e . "</$wrap>";
			}
			return $e;
		}
		return '';
	}

	public function has_errors()
	{
		foreach($this->errors as $error) {
			if (is_array($error)) {
				return true;
			}
		}
		return false;
	}


	public function is_valid()
	{

		$rules = $this->rules;


		/* fix this to handle rules as array instead of just pipe-delimeted strings */

		if ($this->id) {
			foreach($rules as $k=>$v) {
				if (preg_match('/unique:([^\|]+)/', $v, $m)) {
					$parts = explode(',',$m[1]);
					if (count($parts)==1) {
						$parts[] = $k;
					}
					$parts[] = $this->id;
					$rules[$k] = str_replace($m[0], 'unique:'.implode(',',$parts), $v);
				}
			}
		}

		$validator = Validator::make($this->attributes, $rules, $this->messages);

		if ($validator->valid()) {
			$this->errors = array();
			return true;
		} else {
			$this->errors = $validator->errors->messages;
			return false;
		}

	}

}