<?php
namespace magazine;
use think\Request;
use think\Config;

defined('MAGAZINE_VIEW_PATH') or define('MAGAZINE_VIEW_PATH', __DIR__ . DS.'view'. DS);
defined('MAGAZINE_DIR') or define('MAGAZINE_DIR',__DIR__. DS);
defined('MAGAZINE_STYLE_PATH') or define('MAGAZINE_STYLE_PATH','/static/magazine/');

//home
defined('MAGAZINE_HOME_VIEW_PATH') or define('MAGAZINE_HOME_VIEW_PATH', MAGAZINE_DIR . DS.'home'.DS.'view'. DS);
defined('MAGAZINE_HOME_STYLE_PATH') or define('MAGAZINE_HOME_STYLE_PATH','/public/static/magazine/home/');
define('WEIXIN_CACHE_PATH', 'F:\2017_web_sys\huiz_crm_v1.0\runtime');   //

class Base {
	
	public function __construct()
	{
		$this->request      = Request::instance();
		
		//加载组件配置
		Config::load(MAGAZINE_DIR."config.php",'','magazine') ;
		
		//缓存设置
		$cache_config = Config::get("cache",'magazine');
		cache($cache_config);

		
		//参数过滤检测
		$sysconfig_default_filter = Config::get("default_filter");
		if(!$sysconfig_default_filter){
			$default_filter =  Config::get("default_filter",'magazine');
			$this->request->filter($default_filter);
		}
		
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
	
	/**
	 * 加载控制器方法
	 * @access public
	 * @param  string $controller 控制器
	 * @param  string $action 方法名
	 * @return mixed
	 */
	public function autoload2($controller,$action){
	
		$className = '\\magazine\\home\\controller\\'.$controller;
		$controller = new $className($this->request);
		if(method_exists($controller,$action)){
			return  call_user_func([$controller, $action]);
		}
		return false;
	}
	
}
include_once  MAGAZINE_DIR . 'extend/common.php';



