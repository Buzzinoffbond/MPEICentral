<?php defined('SYSPATH') or die('No direct script access.');
 
class Controller_Admin_Ajax extends Controller
{
	public function action_uploadimg()
    {
    	$result = array('code'=>'error');
    	if(Auth::instance()->logged_in('admin'))
    	{	
        	if($this->request->query('id'))
        	{
            	if(Model::factory('Media')->add_image($_FILES['file'],$this->request->query('id')))
            	{
                	$result['code'] = 'success'; 
            	}
        	}
    	}
		$this->response->body(json_encode($result));
    }
    public function action_competitor_image()
    {
        $result = array('code'=>'error');
        if(Auth::instance()->logged_in('admin'))
        {   
            if($this->request->query('id'))
            {
                if(Model::factory('Contest')->add_competitor_image($this->request->query('id'),$_FILES['file']))
                {
                    $result['code'] = 'success'; 
                }
            }
        }
        $this->response->body(json_encode($result));
    }
}