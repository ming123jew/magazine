<?php
namespace magazine\model;

class MagazineArticleStats extends Base
{
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}
	
	protected $autoWriteTimestamp = true;

	// 设置完整的数据表（包含前缀）
	protected $name = 'magazine_article_stats';
	
	
	
	
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
				
			$info = $this->validate('magazine\\validate\\MagazineArticleStats')->saveAll($datas);
			return $info;
	
		}
		return false;
	}
	
}