<?php
namespace magazine\model;

class MagazineAccess extends Base
{
	//初始化属性
	protected function initialize()
	{
		parent::initialize();
	}

	// 设置完整的数据表（包含前缀）
	protected $name = 'magazine_access';
	
	public function GetByCategoryId($id){
		$where['category_id'] = $id;
		$info = $this->all($where);

		if($info){
			
			$res["status"] = true;
			$res["info"] = $info;
		}else{
			$res["status"] = false;
			$res["info"] = 'data is not array.';
		}
		return $res;
	}
	
	/**
	 * @desc   支持批量添加
	 * @param  [] $data
	 * @return Ambigous <multitype:, \think\false, boolean, multitype:Ambigous <\think\$this, \magazine\model\MagazineCategory> >
	 */
	public function Add($data=[]){
		//mysql
		$res=[];
		if (is_array($data)){
			//$datas = [$data];
			$info = $this->validate('magazine\\validate\\MagazineAccess')->saveAll($data);
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
	
	
    /**
	 *  @desc   支持批量删除
	 *  @param  [] $data
     */
    public function Delete($data=[]){
    	if (is_array($data)){
    		return $res = $this->where($data)->delete();
    	}else{
    		return false;
    	}
    }
}