<?php
namespace mediamanage;
use think\Request;

defined('MEDIAMANAGE_VIEW_PATH') or define('MEDIAMANAGE_VIEW_PATH', __DIR__ . DS.'view'. DS);
defined('MEDIAMANAGE_DIR') or define('MEDIAMANAGE_DIR',__DIR__. DS);
defined('MEDIAMANAGE_STYLE_PATH') or define('MEDIAMANAGE_STYLE_PATH', '/public/static/mediamanage/webuploader-0.1.5/');
class Base {
	
	public function __construct()
	{
		$this->request      = Request::instance();
		$this->param        = $this->request->param();
		$this->module       = $this->request->module();
		$this->controller   = $this->request->controller();
		$this->action       = $this->request->action();
		
	}
	
	/**
	 * 加载控制器方法
	 * @access public
	 * @param  string $controller 控制器
	 * @param  string $action 方法名
	 * @return mixed
	 */
	public function autoload($controller,$action){
		$className = '\\mediamanage\\controller\\'.$controller;
		
		$controller = new $className($this->request);

		if(method_exists($controller,$action)){
			
			return  call_user_func([$controller, $action]);
		}
	
		return false;
	}
	
	
}



