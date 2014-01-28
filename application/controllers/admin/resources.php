<?php

class Admin_Resources_Controller extends Base_Controller {


	public function __construct()
	{
		$this->filter('before', 'auth');
		$this->filter('before', 'csrf')->on('post');
		parent::__construct();

	}

	public function get_index($id=null)
	{

		$resourcegroups = Resourcegroup::order_by('sort_order','asc')->get();

		$show_group = $id ? Resourcegroup::find($id) : null;

		$data = array(
			'resourcegroups' => $resourcegroups,
			'last_group'     => end($resourcegroups)->sort_order,
			'first_group'    => reset($resourcegroups)->sort_order,
			'show_group'     => $show_group,
		);
		if ($show_group) {
			$resources = $show_group->resources;
			if (count($resources)) {
				$data['last']  = end($resources)->sort_order;
				$data['first'] = reset($resources)->sort_order;
			}
		}


		$this->layout->with('title', 'Resources')
			->nest('content', 'admin.resources.index', $data );
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
			'private'    => Input::get('private',0),
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


	public function get_edit_group($id)
	{
		$group = Resourcegroup::find($id);

		$this->layout->with('title', 'Edit Resource Group')
			->nest('content', 'admin.resources.group_form', array(
				'group'       => $group,
				'title'       => 'Edit Group',
				'submit_text' => 'Edit Group',
				'mode'        => 'edit'
			));
	}


	public function post_edit_group($id)
	{

		$group = Resourcegroup::find($id);

		$group->fill(array(
			'title'      => Input::get('title'),
			'private'    => Input::get('private',0),
		));


		if ($group->is_valid()) {
			$group->save();
			return Redirect::to_action('admin.resources')
				->with('success', 'Resource Group "' . $group->title . '" edited.');
		}


		$this->layout->with('title', 'Edit Group')
			->nest('content', 'admin.resources.group_form', array(
				'group'       => $group,
				'title'       => 'Edit Group',
				'submit_text' => 'Edit Group',
				'mode'        => 'edit'
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



	public function get_delete_group($id)
	{

		if (!$group = Resourcegroup::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		$this->layout->with('title', 'Delete Resource Group')
			->nest('content', 'admin.resources.group_delete', array(
				'group'    => $group,
			));

	}


	public function post_delete_group($id)
	{

		if (!$group = Resourcegroup::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.resources@delete_group', array($id))
				->with('warning', 'Resource group not deleted &mdash; confirmation not checked.');
		}

		$group->delete();
		return Redirect::to_action('admin.resources')
			->with('success', 'Resource group "' . $group->title . '" deleted.');

	}





	public function get_new($group_id)
	{
		$resource = new Resource(array(
			'resourcegroup_id' => $group_id
		));

		$this->layout->with('title', 'New Resource')
			->nest('content', 'admin.resources.form', array(
				'resource'    => $resource,
				'title'       => 'New Resouce',
				'submit_text' => 'Add Resource',
				'mode'        => 'new'
			));
	}


	public function post_new($group_id)
	{

		$resource = new Resource(array(
			'resourcegroup_id' => $group_id,
			'title'            => Input::get('title'),
			'description'      => Input::get('description'),
			'active'           => Input::get('active',0),
			'sort_order'       => Resource::last_sort() + 1,
		));

		$file = Input::file('url_file');
		if ( $file && $file['error']==UPLOAD_ERR_OK ) {
			$filename = Str::random(16);
			Input::upload('url_file', path('uploads'), $filename);
			$resource->url = 'file://' . $filename . '/' . $file['name'];
		} else {
			$resource->url = protofy(Input::get('url_link'));
		}


		if ( $resource->is_valid() ) {
			$resource->save();
			return Redirect::to_action('admin.resources', array($group_id))
				->with('success', 'Resource "' . $resource->title . '" added.');
		}

		$resource->delete_file();

		$this->layout->with('title', 'New Resource')
			->nest('content', 'admin.resources.form', array(
				'resource'    => $resource,
				'title'       => 'New Resouce',
				'submit_text' => 'Add Resource',
				'mode'        => 'new'
			));
	}





	public function get_edit($id)
	{
		$resource = Resource::find($id);

		$this->layout->with('title', 'Edit Resource')
			->nest('content', 'admin.resources.form', array(
				'resource'    => $resource,
				'title'       => 'Edit Resource',
				'submit_text' => 'Edit Resource',
				'mode'        => 'edit'
			));
	}


	public function post_edit($id)
	{

		$resource = Resource::find($id);

		$resource->fill(array(
			'title'       => Input::get('title'),
			'description' => Input::get('description'),
			'active'      => Input::get('active',0),
		));

		$newfile = Input::file('url_file');

		if ( $newfile && $newfile['error']!=UPLOAD_ERR_NO_FILE) {
			if ( $newfile['error']==UPLOAD_ERR_OK ) {
				$filename = Str::random(16);
				Input::upload('url_file', path('uploads'), $filename);
				$resource->url = 'file://' . $filename . '/' . $newfile['name'];
			}
		} else if ( Input::get('url_link') ) {
			$resource->url = protofy(Input::get('url_link'));
		}

		if ($resource->is_valid()) {
			$resource->save();
			return Redirect::to_action('admin.resources', array($resource->resourcegroup_id) )
				->with('success', 'Resource "' . $resource->title . '" edited.');
		}

		$resource->delete_file();

		$this->layout->with('title', 'Edit Resource')
			->nest('content', 'admin.resources.form', array(
				'resource'    => $resource,
				'title'       => 'Edit Resource',
				'submit_text' => 'Edit Resource',
				'mode'        => 'edit'
			));

	}




	public function get_sort($dir, $id)
	{

		if (!$resource = Resource::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		$group_id = $resource->resourcegroup_id;

		$other = null;

		switch($dir) {
			case 'up':
				$other = Resource::where('sort_order', '<', $resource->sort_order)
					->where('resourcegroup_id','=',$group_id)
					->order_by('sort_order', 'desc')
					->first();
				break;
			case 'down':
				$other = Resource::where('sort_order', '>', $resource->sort_order)
					->where('resourcegroup_id','=',$group_id)
					->order_by('sort_order', 'asc')
					->first();
				break;
		}

		if (!$other) {
			return Redirect::to_action('admin.resources', array($group_id) )
				->with('error', 'Invalid request');
		}

		$temp = $other->sort_order;
		$other->sort_order = $resource->sort_order;
		$resource->sort_order = $temp;

		if ($resource->save() && $other->save()) {
			return Redirect::to_action('admin.resources', array($group_id))
				->with('success', 'Resources resorted');
		}

		return Redirect::to_action('admin.resources', array($group_id))
			->with('error', 'Could not resort resources');

	}


	public function get_delete($id)
	{

		if (!$resource = Resource::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		$this->layout->with('title', 'Edit Resource')
			->nest('content', 'admin.resources.delete', array(
				'resource'    => $resource,
			));

	}


	public function post_delete($id)
	{

		if (!$resource = Resource::find($id)) {
			return Redirect::to_action('admin.resources')
				->with('error', 'Invalid request');
		}

		if ( !Input::get('confirm') ) {
			return Redirect::to_action('admin.resources@delete', array($id))
				->with('warning', 'Resource not deleted &mdash; confirmation not checked.');
		}

		$resource->delete();
		return Redirect::to_action('admin.resources', array($resource->resourcegroup_id))
			->with('success', 'Resource "' . $resource->title . '" deleted.');

	}


}
