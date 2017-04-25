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
	public $username			= 'admin';
	
	//权限
	public $MAGAZINE_role_table_name = "";
	public $MAGAZINE_role_id = "";
	public $MAGAZINE_role_name = "";
	public $MAGAZINE_admin_table_name = "";
	public $MAGAZINE_admin_role = "";
	
	public function __construct($request)
	{	
		//如果设置了session信息则,读取session
		$MAGAZINE_admin_session = Config::get("MAGAZINE_admin_session",'magazine');
		
		if($MAGAZINE_admin_session){
			$this->user = session($MAGAZINE_admin_session);
		}
		$MAGAZINE_admin_uid = Config::get("MAGAZINE_admin_uid",'magazine');
		if($MAGAZINE_admin_uid && !array_key_exists('uid',$this->user)){
			$this->uid = $this->user[$MAGAZINE_admin_uid];
			$this->user['uid'] = $this->uid;
		}else{
			$this->uid = $this->user['uid'];
		}
		$MAGAZINE_admin_username = Config::get("MAGAZINE_admin_username",'magazine');
		if($MAGAZINE_admin_username){
			$this->username = $this->user[$MAGAZINE_admin_username];
		}
		
		
		$this->request  = $request;
		$this->param    = $this->request->param();
		$this->post     = $this->request->post();
		$this->id       = isset($this->param['id'])?intval($this->param['id']):'';
		$this->data     = ['pach'=>MAGAZINE_VIEW_PATH,'static'=>MAGAZINE_STYLE_PATH];
	}
	
	public function Tree($datas,$selected = 1,$field='id'){
		$tree = new Tree();
		$array = [];
		foreach ($datas as $r) {
			$r['selected'] = $r[$field] == $selected ? 'selected' : '';
			$array[] = $r;
		}
		$str = "<option value='\$id' \$selected>\$spacer \$name</option>";
		
		$tree->init($array);
		$parentid = isset($where['parentid'])?$where['parentid']:0;
	
		return $tree->get_tree($parentid, $str);
	}
	
}