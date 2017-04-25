<?php
namespace magazine\controller;
use think\Cache;
use magazine\controller\Base;
class Setting extends Base
{
	function __construct($request){
		parent::__construct($request);
	}
	/**
	 * 列表
	 */
	function Index(){
		
		return [MAGAZINE_VIEW_PATH.'setting/index.php',array_merge($this->data)];
	}
	
	
	public function ClearnCache(){
		
		//加载组件配置
		//$cache_config = Config::get("cache",'magazine');
		Cache::clear('magazine');
		echo '缓存清除成功';
		return ['code'=>1,'msg'=>'清理成功.'];
	}

}
