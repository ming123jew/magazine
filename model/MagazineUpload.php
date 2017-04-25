<?php
namespace magazine\model;

class MagazineUpload extends Base
{
	
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}

	// 设置完整的数据表（包含前缀）
	protected $name = 'magazine_upload';
	protected $autoWriteTimestamp = true;
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
			
			$info = $this->validate('magazine\\validate\\MagazineUpload')->saveAll($datas);
			if(false === $info){
				// 验证失败 输出错误信息
				$info = $this->getError();
				$res["status"] = false;
				$res["info"] = $info;
				 
			}else{
				$res["status"] = true;
				$res["info"] = $info;
			}
	
		}else{
			$res["status"] = false;
			$res["info"] = 'data is not array.';
		}
		return $res;
	}
}