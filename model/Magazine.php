<?php
namespace magazine\model;

class Magazine extends Base
{
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}
	
	protected $autoWriteTimestamp = true;

	// 设置完整的数据表（包含前缀）
	protected $name = 'magazine';
	
	//一对一关联MagazineStats
	public function MagazineStats(){
		return $this->hasOne('MagazineStats','magazine_id');
	}
	
	/**
	 * 列表
	 * @param number $pagesize
	 * @return array
	 */
	public function Lists($category_id=0,$pagesize=10,$status='all'){
		
		if($status=='-1' || $status=='1' || $status=='0' ){
			$whereStatus = [
			'status'=> $status
			];
		}else{
			
			$whereStatus = 'status >= 0';
		}
		
		//如启用mongodb则使用mongodb
		if($this->mongodb){
			$res = $this->mongodbLists($pagesize);
			return $res;
			exit(0);
		}
	
		//mysql
		// 查询状态为1的用户数据 并且每页显示10条数据
		if($category_id>0){
			$list = self::with('MagazineStats')->where(['category_id'=>$category_id])->where($whereStatus)->paginate($pagesize);
		}else{
			$list = self::with('MagazineStats')->where($whereStatus)->paginate($pagesize);
		}
		
		// 获取分页显示
		$page = $list->render();
	
		$res['list'] = $list->items();
		$res['render'] = $list;
		$res['page'] = $page;
		return $res;
	}
	
	/**
	 * @desc   支持批量添加
	 * @param  [] $data
	 * @return Ambigous <multitype:, \think\false, boolean, multitype:Ambigous <\think\$this, \magazine\model\MagazineCategory> >
	 */
	public function Add($data=[]){
			
		if (count($data) == count($data, 1)) {
			$datas = [$data];//一维数组
		} else {
			$datas = $data;//二维数组
		}
		//mysql
		$res=[];
		if (is_array($data)){
			$info = $this->validate('magazine\\validate\\Magazine')->saveAll($datas);
			return $info;
		}
		return false;
	}
	
}