<?php
namespace magazine\home\controller;
use magazine\library\Tree;
use magazine\library\wxsdk\Jssdk;
use think\Config;

class Base {
	
	const  PATH                 = __DIR__;
	public $request				= "";
	public $param				= "";
	public $post				= "";
	public $id					= 0 ;
	public $data				= "";
	public $shareData 			= "";

	public function __construct($request)
	{	
		$this->request  = $request;
		$this->param    = $this->request->param();
		$this->post     = $this->request->post();
		$this->id       = isset($this->param['id'])?intval($this->param['id']):'';
		$this->data     = ['pach'=>MAGAZINE_VIEW_PATH,'static'=>MAGAZINE_STYLE_PATH];
		$this->shareData = self::_WxShareCode();
		
	}
	
	/**
	 * @desc 获取分享代码
	 * @return array
	 */
	protected  function _WxShareCode(){
		$appId = Config::get('wx.appId','magazine');
		$appSecret = Config::get('wx.appSecret','magazine');
		$Jssdk = new Jssdk($appId, $appSecret);
		return $Jssdk->getSignPackage();
	}
	
	public function Tree($datas,$selected = 1,$field='id'){
		$tree = new Tree();
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