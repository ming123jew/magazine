<?php
namespace magazine\controller;
use magazine\controller\Base;
use magazine\model\MagazineCategory;
use magazine\model\MagazineAccess;
use think\Config;
use magazine\library\Tree;


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
		$this->MAGAZINE_role_table_name = Config::get("MAGAZINE_role_table_name",'magazine');
		$this->MAGAZINE_role_name = Config::get("MAGAZINE_role_name",'magazine');
		$this->MAGAZINE_role_id = Config::get("MAGAZINE_role_id",'magazine');
		
		$this->MAGAZINE_admin_table_name = Config::get("MAGAZINE_admin_table_name",'magazine');
		$this->MAGAZINE_admin_role = Config::get("MAGAZINE_admin_role",'magazine');
		if($this->MAGAZINE_role_table_name && $this->MAGAZINE_admin_role && $this->MAGAZINE_admin_table_name){
			$this->isRole = true;
			$this->data['isRole']=true;
		}
	}
	
	
	public function Index(){
		//return ['code'=>11,'msg'=>'test'];
		//print_r($this->user);
		// 查询状态为1的用户数据 并且每页显示10条数据
		$model = new MagazineCategory;
		$result = $model->Lists(1000);
		
		if($result['list']){
			$arr = collection($result['list'])->toArray();
			
			//树状输出
			$tree       = new Tree();
			foreach ($arr as $n=> $r) {
				$arr[$n]['level'] = $tree->get_level($r['id'], $arr);
				$arr[$n]['parent_id_node'] = ($r['parent_id']) ? ' class="child-of-node-' . $r['parent_id'] . '"' : '';
			
				$arr[$n]['str_manage'] =  '<a href="'.url("Edit",["id" => $r['id'],"parent_id"=>$r["parent_id"]]).'">编辑</a> | ';
				$arr[$n]['str_manage'] .='<a href="'.url("Role",["id" => $r['id']]).'">权限设置</a> | ';
				$arr[$n]['str_manage'] .='<a class="a-post" href="#" post-url="'.url("Delete",["id" => $r['id']]).'">删除</a>';
				//$result[$n]['str_manage'] .= checkPath('auth/menuDelete',["id" => $r['id']]) ?'<a class="a-post" post-msg="你确定要删除吗" post-url="'.url("auth/menuDelete",["id" => $r['id']]).'">删除</a>|':'';
				
				$arr[$n]['status'] = $r['status'] ? '开启' : '隐藏';
			}
			
			$str = "<tr id='node-\$id' \$parent_id_node>
                    <td style='padding-left:20px;'>
                       
						\$id
                    </td>
                    <td>\$spacer  \$name</td>
                    <td>\$count</td>
                    <td>\$status</td>
                    <td>\$str_manage</td>
                </tr>";
			$tree->init($arr);
			$info = $tree->get_tree(0, $str);
			
		}else{
			$info ="no data";
		}

		return [MAGAZINE_VIEW_PATH.'category/index.php',array_merge($this->data,['info'=>$info])];
	}
	
	public function Add(){
		$model_MagazineCategory = new MagazineCategory();
		if($this->request->isPost()){
			$data = $this->post;
			$data['uid'] = $this->uid;
			$data['count'] = 0;
			$res = $model_MagazineCategory->Add($data);
			if($res["status"]){
				return ['code'=>1,'msg'=>'添加成功','url'=>url('category/index')];
			}else{
				//echo $res['info'];
				return ['code'=>0,'msg'=>$res['info']];
			}
		}else{
			$conllection_data = $model_MagazineCategory->Alls(false);
			if($conllection_data){
				$conllection_data = collection($conllection_data)->toArray();
			}else{
				return ['code'=>0,'msg'=>'no data.'];
			}
			$tree = self::Tree($conllection_data,0);
			return [MAGAZINE_VIEW_PATH.'category/add.php',array_merge($this->data,['selectCategorys'=>$tree])];
		}
		
	}
	
	/**
	 * @desc   修改分类
	 * @return multitype:number string |multitype:number Ambigous <Ambigous <\think\$this, \magazine\model\MagazineCategory>> |multitype:string multitype:
	 */
	public function Edit(){
		
		$model_MagazineCategory = new MagazineCategory();
		if($this->request->isPost()){
			$data = $this->post;
			$data['uid'] = $this->uid;
			$res = $model_MagazineCategory->Edit($data);
			if($res["status"]){
				return ['code'=>1,'msg'=>'修改成功','url'=>url('category/index')];
			}else{
				//echo $res['info'];
				return ['code'=>0,'msg'=>$res['info']];
			}
		
		}else{
			$parent_id = $this->request->param('parent_id');
			$parent_id = intval($parent_id);
			$conllection_data = $model_MagazineCategory->Alls(false);
			if($conllection_data){
				$conllection_data = collection($conllection_data)->toArray();
			}else{
				return ['code'=>0,'msg'=>'no data.'];
			}
			$tree = self::Tree($conllection_data,$parent_id);
			
			$res = $model_MagazineCategory->Find($this->id);
			
			if($res["status"]){
				return [MAGAZINE_VIEW_PATH.'category/edit.php',array_merge($this->data,['list'=>$res],['selectCategorys'=>$tree])];
			}else{
				return ['code'=>0,'msg'=>$res['info']];
			}
		}
	}
	
	public function Delete(){
		$id = $this->request->param('id');
		$id = intval($id);
		
		$model_MagazineCategory = new MagazineCategory();
		$where_MagazineCategory = [
			'id'=>$id
		];
		$data_MagazineCategory = [
			'status'=>0
		];
		$result_update_MagazineCategory = $model_MagazineCategory->save($data_MagazineCategory,$where_MagazineCategory);
		if($result_update_MagazineCategory){
			return ['code'=>1,'msg'=>'删除成功.','url'=>url('category/index')];
		}else{
			return ['code'=>0,'msg'=>$model_MagazineCategory->getError()];
		}
	}
	
	
	private function _RoleBefore($category_id){
		$data['category_id'] = $category_id;
		$model_MagazineAccess = new MagazineAccess();
		$res = $model_MagazineAccess->Delete($data);
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
			
			$model_MagazineAccess = new MagazineAccess();
			$res = $model_MagazineAccess->Add($data);
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
			$model_MagazineCategory = new MagazineCategory();
			$res = $model_MagazineCategory->Find($this->id);
			
			//查找栏目对应权限
			$model_MagazineAccess = new MagazineAccess();
			$res2 = $model_MagazineAccess->GetByCategoryId($this->id);
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