<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin_User extends Controller_Admin_CommonAdmin {

	public function action_index(){
		$this->template->content = View::factory('admin/users/user-list')
			->bind('users', $users)
			->bind('total_users_count', $total_users_count)
			->bind('pagination',$pagination);

		$total_users_count = Model::factory('Users')->count_all();
		$pagination = Pagination::factory(array(
			'total_items'=>$total_users_count,
			'items_per_page'=>30));
		$pagination->route_params(array('controller' => $this->request->controller(), 'action' => $this->request->action()));
		$users = Model::factory('Users')->get_page_of_users($pagination->offset,$pagination->items_per_page);
	}
	public function action_edit(){
		$username = $this->request->param('username');
		if (!$username)
			{
				HTTP::redirect('admin/user');
			}
		$this->template->content = View::factory('admin/users/edit')
		->bind('user', $user);

		$user = Model::factory('Users')->get_user_info($username);
	}
	public function action_delete(){
		if(Model::factory("Users")->delete_user($this->request->param('username'))){
			HTTP::redirect();
		}
	}
}