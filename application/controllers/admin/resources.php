<?php

class Admin_Resources_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}

	public function get_index()
	{
		$this->layout->with('title', 'Resources')
			->nest('content', 'admin.resources.index', array(
				'resourcegroups' => Resourcegroup::order_by('sort_order','asc')->get()
			));
	}


	public function get_new_group()
	{
		$group = new Resourcegroup;

		$this->layout->with('title', 'New Resource Group')
			->nest('content', 'admin.resources.group_form', array(
				'group'       => $group,
				'title'       => 'New Group',
				'submit_text' => 'Add Group',
				'mode'        => 'new'
			));
	}




	public function post_new_group()
	{

		$group = new Resourcegroup;

		$group->fill(array(
			'title'      => Input::get('title'),
			'sort_order' => Resourcegroup::last_sort() + 1,
		));


		if ($group->is_valid()) {
			$group->save();
			return Redirect::to_action('admin.resources')
				->with('success', 'Resource Group "' . $group->title . '" added.');
		}


		$this->layout->with('title', 'New Group')
			->nest('content', 'admin.resources.group_form', array(
				'group'       => $group,
				'title'       => 'New Group',
				'submit_text' => 'Add Group',
				'mode'        => 'new'
			));

	}


	public function get_sort_group($dir, $id)
	{

		if (!$group = Resourcegroup::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		$other_group = null;

		switch($dir) {
			case 'up':
				$other_group = Resourcegroup::where('sort_order', '<', $group->sort_order)
					->order_by('sort_order', 'desc')
					->first();
				break;
			case 'down':
				$other_group = Resourcegroup::where('sort_order', '>', $group->sort_order)
					->order_by('sort_order', 'asc')
					->first();
				break;
		}

		if (!$other_group) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		$temp = $other_group->sort_order;
		$other_group->sort_order = $group->sort_order;
		$group->sort_order = $temp;

		if ($group->save() && $other_group->save()) {
			return Redirect::to_action('admin.resources')
				->with('success', 'Groups resorted');
		}

		return Redirect::to_action('admin.resources')
			->with('error', 'Could not resort groups');

	}

}
