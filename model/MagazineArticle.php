<?php
namespace magazine\model;

class MagazineArticle extends Base
{
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}
	
	protected $autoWriteTimestamp = true;

	// 设置完整的数据表（包含前缀）
	protected $name = 'magazine_article';
	
	//一对一关联MagazineStats
	public function MagazineArticleStats(){
		return $this->hasOne('MagazineArticleStats','magazine_article_id');
	}
	/**
	 * 列表
	 * @param number $pagesize
	 * @return array
	 */
	public function Lists($magazine_id,$catalogue_id=0,$pagesize=10,$status='all'){
		 
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
		if($catalogue_id>0){
			
			$list = self::with('MagazineArticleStats')->where(['magazine_id'=>$magazine_id,'catalogue_id'=>$catalogue_id])
			->where($whereStatus)
			->paginate($pagesize);
		}else{
			$list = self::with('MagazineArticleStats')->where(['magazine_id'=>$magazine_id])
			->where($whereStatus)
			->paginate($pagesize);
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
				
			$info = $this->validate('magazine\\validate\\MagazineArticle')->saveAll($datas);
			return $info;
	
		}
		return false;
	}
	
}