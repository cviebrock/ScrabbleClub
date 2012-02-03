<?php

class BaseModel extends Eloquent {

	public $rules = array();

	public $errors = array();

	public $help = array();

/*
	public function errors($field=null)
	{
		if ($field) {
			return $this->errors[$field];
		}
		return $this->errors;
	}

	public function all_errors()
	{
		if ($this->errors==null) {
			return array();
		}
		return $this->errors->all();
	}
*/

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

		$validator = Validator::make($this->attributes, $rules);

		if ($validator->valid()) {
			$this->errors = array();
			return true;
		} else {
			$this->errors = $validator->errors->messages;
			return false;
		}

	}

}