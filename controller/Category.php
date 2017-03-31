<?php
namespace magazine\controller;
use magazine\controller\Base;
use magazine\model\MediamanageCategory;
use magazine\model\MediamanageAccess;
use think\Config;



/**
 * 分类管理
 * @author ming123jew
 *
 */
class Category extends Base
{
	
	protected $isRole = false;
	
	
	public function __construct($request){
		parent::__construct($request);
		
		//如设置了　管理员表　角色表　　管理员表字段　则开放栏目权限功能
		$this->MAGAZINE_role_table_name = Config::get("MAGAZINE_role_table_name");
		$this->MAGAZINE_role_name = Config::get("MAGAZINE_role_name");
		$this->MAGAZINE_role_id = Config::get("MAGAZINE_role_id");
		
		$this->MAGAZINE_admin_table_name = Config::get("MAGAZINE_admin_table_name");
		$this->MAGAZINE_admin_role = Config::get("MAGAZINE_admin_role");
		if($this->MAGAZINE_role_table_name && $this->MAGAZINE_admin_role && $this->MAGAZINE_admin_table_name){
			$this->isRole = true;
			$this->data['isRole']=true;
		}
	}
	
	
	public function Index(){
		//return ['code'=>11,'msg'=>'test'];
		//print_r($this->user);
		// 查询状态为1的用户数据 并且每页显示10条数据
		$model = new MediamanageCategory;
		$info = $model->Lists(10);
		

		return [MAGAZINE_VIEW_PATH.'category/index.php',array_merge($this->data,['list'=>$info['list'],'page'=>$info['page']])];
	}
	
	public function Add(){

		if($this->request->isPost()){
			$model_MediamanageCategory = new MediamanageCategory();
			$data = $this->post;
			$data['uid'] = $this->uid;
			$data['count'] = 0;
			$res = $model_MediamanageCategory->Add($data);
			if($res["status"]){
				return ['code'=>1,'msg'=>'添加成功','url'=>url('category/index')];
			}else{
				//echo $res['info'];
				return ['code'=>0,'msg'=>$res['info']];
			}
		}else{
			return [MAGAZINE_VIEW_PATH.'category/add.php',array_merge($this->data)];
		}
		
	}
	
	public function Edit(){
		
		$model_MediamanageCategory = new MediamanageCategory();
		if($this->request->isPost()){
			
			$data = $this->post;
			$data['uid'] = $this->uid;
			$res = $model_MediamanageCategory->Edit($data);
			if($res["status"]){
				return ['code'=>1,'msg'=>'修改成功','url'=>url('category/index')];
			}else{
				//echo $res['info'];
				return ['code'=>0,'msg'=>$res['info']];
			}
		
		}else{
			$res = $model_MediamanageCategory->Find($this->id);
			
			if($res["status"]){
				return [MAGAZINE_VIEW_PATH.'category/edit.php',array_merge($this->data,['list'=>$res['info']])];
			}else{
				return ['code'=>0,'msg'=>$res['info']];
			}
		}
		
	}
	
	
	private function _RoleBefore($category_id){
		$data['category_id'] = $category_id;
		$model_MediamanageAccess = new MediamanageAccess();
		$res = $model_MediamanageAccess->Delete($data);
	}
	
	/**
	 * @desc   栏目权限设置：  增　删　改
	 * @return multitype:number string
	 */
	public function Role(){
		
		if($this->request->isPost()){
			
			//$data['category_id'] = $this->post['id'];
			foreach ($this->post['roleid'] as $key=>$value){
				foreach ($value as $k=>$v){
					
						$data[] = ['category_id'=> $this->post['id'],'role'=>$key,'action'=>$v];
					
				}
			}
			
			//修改权限之前先对需要修改的　角色和栏目　对应的数据处理
			$this->_RoleBefore($this->post['id']);
			
			$model_MediamanageAccess = new MediamanageAccess();
			$res = $model_MediamanageAccess->Add($data);
			if($res["status"]){
				return ['code'=>1,'msg'=>'完成设置','url'=>url('category/index')];
			}else{
				//echo $res['info'];
				return ['code'=>0,'msg'=>$res['info']];
			}
			
			
		}else{
			
			//权限表
			$role = db($this->MAGAZINE_role_table_name)->select();
			$role = collection($role)->toArray();
			
			//查找栏目信息
			$model_MediamanageCategory = new MediamanageCategory();
			$res = $model_MediamanageCategory->Find($this->id);
			
			
			//查找栏目对应权限
			$model_MediamanageAccess = new MediamanageAccess();
			$res2 = $model_MediamanageAccess->GetByCategoryId($this->id);
			if($res2['status']){
				$res2['info'] = collection($res2['info'])->toArray();
				
				foreach ($role as $key => $value){
					foreach ($res2['info'] as $k => $v){
						
						if($value[$this->MAGAZINE_role_id]==$v['role']){
							$role[$key][$v['action']] = 'checked';
						}
						//print_r($value);
					}
				}
				
				//print_r($role);
			}

			
			if($res["status"]){
				return [MAGAZINE_VIEW_PATH.'category/role.php',
						array_merge($this->data,
									['roleList'=>$role,
									'list'=>$res['info'],
									'role_name'=> $this->MAGAZINE_role_name,
									'role_id' => $this->MAGAZINE_role_id,
									]
						)
				];
			}else{
				return ['code'=>0,'msg'=>$res['info']];
			}
		}
	}
	
}