<?php
namespace magazine\controller;
use think\Config;
use magazine\library\Tree;
class Base {
	
	const  PATH                 = __DIR__;
	public $request				= "";
	public $param				= "";
	public $post				= "";
	public $id					= 0 ;
	public $data				= "";
	public $user				= "";
	public $uid					= 0;
	
	
	//权限
	public $MAGAZINE_role_table_name = "";
	public $MAGAZINE_role_id = "";
	public $MAGAZINE_role_name = "";
	public $MAGAZINE_admin_table_name = "";
	public $MAGAZINE_admin_role = "";
	
	public function __construct($request)
	{	
		//加载组件配置
		Config::load(MAGAZINE_DIR."config.php") ;
		$cache_config = Config::get("cache");
		cache($cache_config);
		
		
		//如果设置了session信息则,读取session
		$MAGAZINE_admin_session = Config::get("MAGAZINE_admin_session");
		if($MAGAZINE_admin_session){
			$this->user = session($MAGAZINE_admin_session);
		}
		$MAGAZINE_admin_uid = Config::get("MAGAZINE_admin_uid");
		if($MAGAZINE_admin_uid && !array_key_exists('uid',$this->user)){
			$this->uid = $this->user[$MAGAZINE_admin_uid];
			$this->user['uid'] = $this->uid;
		}else{
			$this->uid = $this->user['uid'];
		}
		
		$this->request  = $request;
		$this->param    = $this->request->param();
		$this->post     = $this->request->post();
		$this->id       = isset($this->param['id'])?intval($this->param['id']):'';
		$this->data     = ['pach'=>MAGAZINE_VIEW_PATH,'static'=>MAGAZINE_STYLE_PATH];
	}
	
	public function Tree($datas,$selected = 1){
		$tree = new Tree();
		foreach ($datas as $r) {
			$r['selected'] = $r['id'] == $selected ? 'selected' : '';
			$array[] = $r;
		}
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		$tree->init($array);
		$parentid = isset($where['parentid'])?$where['parentid']:0;
	
		return $tree->get_tree($parentid, $str);
	}
	
}