<?php
namespace magazine;
use think\Request;

defined('MAGAZINE_VIEW_PATH') or define('MAGAZINE_VIEW_PATH', __DIR__ . DS.'view'. DS);
defined('MAGAZINE_DIR') or define('MAGAZINE_DIR',__DIR__. DS);

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
		$className = '\\magazine\\controller\\'.$controller;
		
		$controller = new $className($this->request);

		if(method_exists($controller,$action)){
			
			return  call_user_func([$controller, $action]);
		}
	
		return false;
	}
	
	
}



