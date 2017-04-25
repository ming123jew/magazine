<?php
namespace magazine\controller;
use magazine\controller\Base;
use magazine\model\MagazineCategory;
use magazine\model\MagazineUpload;
use think\Config;
use magazine\model\MagazineCatalogue;
use magazine\model\Magazine;
use magazine\model\MagazineStats;
use magazine\model\MagazineArticle;
use magazine\model\MagazineArticleStats;
use magazine\model\MagazineUeditor;

class Content extends Base{
	
	
	public function __construct($request){
		parent::__construct($request);
	}
	
	/**
	 * @desc   主页面显示   页面架构显示
	 * @return multitype:string multitype:
	 */
	public function Index(){
		return [MAGAZINE_VIEW_PATH.'index/index.php',array_merge($this->data)];
	}
	
	/**
	 * @desc   content/index iframe 栏目菜单
	 * @return multitype:string multitype:
	 */
	public function Menu(){
		$model = new MagazineCategory;
		$result = $model->Lists(1000);
		if($result['list']){
			$arr = collection($result['list'])->toArray();
			//树状输出
			//重新分组
			$new_arr = $this->_get_tree( $arr,0);
			$str = $this->_procHtml($new_arr);
		}else{
			$str = "no data";
		}
		$info = "";
		return [MAGAZINE_VIEW_PATH.'index/menu.php',array_merge($this->data,['menuList'=>$str])];
	}
	
	/**
	 * @desc 递归创建数组
	 */
	private function _get_tree($arr,$parent_id){

		$tree = [];
		foreach($arr as $k => $v)
		{
		   if($v['parent_id'] == $parent_id)
		   {         //父亲找到儿子
		    $v['parent_id_s'] = self::_get_tree($arr, $v['id']);
		    $tree[] = $v;
		    //unset($tree[$k]);
		   }
		}
		return $tree;
	}
	
	/**
	 * @desc   转成多维数组，找到子类
	 * @param  $tree
	 * @return string
	 */
	private function _procHtml($tree)
	{
		
		$html = '';
		foreach($tree as $t)
		{
			if( empty($t['parent_id_s']) )
			{
				$html .= "<li><span class='file'><a style='text-decoration: none;color: #444;' href='".url('content/lists',['id'=>$t['id']])."' target='rightMain'>{$t['name']}</a></span></li>";
			}
			else
			{
				$html .= "<li><span class='folder'><a style='text-decoration: none;color: #444;' href='".url('content/lists',['id'=>$t['id']])."' target='rightMain'>".$t['name']."</a></span><ul>";
				$html .=self::_procHtml($t['parent_id_s']);
				$html = $html."</ul></li>";

			}
		}
		return $html ? '<ul>'.$html.'</ul>' : $html ;
	}
	
	/**
	 * @desc  周刊列表
	 * @param number $category_id
	 * @return multitype:string multitype:
	 */
	public function Lists(){
		//分类id
		$category_id = $this->request->param('id');
		$category_id = intval($category_id);
		$model_Magzine = new Magazine();
		$resultSet_Magzine = $model_Magzine->Lists($category_id,20);
		if($resultSet_Magzine['list']){
			$resultSet_Magzine['list'] = collection($resultSet_Magzine['list'])->toArray();
		}
		//print_r($resultSet_Magzine['list']);
		$template_data = array_merge(
				$this->data,
				['category_id'=>$category_id],
				['list'=>$resultSet_Magzine['list']],
				['page'=>$resultSet_Magzine['page']]
				);
		return [MAGAZINE_VIEW_PATH.'index/lists.php',$template_data];
	}
	
	
	/**
	 * @desc  添加周刊
	 * @return multitype:string multitype:
	 */
	public function Add(){
		//post-start
		if($this->request->isPost()){
			$post_datas = $this->request->post();
			$post_data = $post_datas['info'];

			//插入主表
			$insert_magazin = $this->_DealMagazine($post_data);
			if(!isset($insert_magazin['id'])){
				return ['code'=>0,'msg'=>$insert_magazin];exit(0);
			}
			
			if($insert_magazin['id'] && $post_data['catalogue']){
				
				//更新图片各id
				$model_MagazineUpload = new MagazineUpload();
				$update_data_MagazineUpload = [
					'had'=>1,
					'magazine_id'=>$insert_magazin['id'],
					'category_id'=>intval($post_data['category_id'])
				];
				$update_where_MagazineUpload = [
					'token'=>$post_datas['__token__'],
				];
				$model_MagazineUpload->update($update_data_MagazineUpload,$update_where_MagazineUpload);
				
				//插入统计表
				$model_MagazineStats = new MagazineStats();
				$insert_magazinStats = [
					'magazine_id'=>$insert_magazin['id'],
					'category_id'=>intval($post_data['category_id'])
				];
				$res_insert_magazinStats = $model_MagazineStats->Add($insert_magazinStats);
				//插入目录表 处理catalogue 目录数据
				//print_r($post_data['catalogue']);
				$array_catalogue = $this->_DealCatalogue($insert_magazin['id'],$post_data['category_id'],$post_data['catalogue']);
				if($array_catalogue && $res_insert_magazinStats){
					return ['code'=>1,'msg'=>$array_catalogue,'url'=>url('content/lists',['id'=>$post_data['category_id']])];exit(0);
				}else{
					return ['code'=>0,'msg'=>$array_catalogue,];exit(0);
				}
			}	
			//print_r($post_data);
			return ['code'=>0,'msg'=>"error."];exit(0);
		}
		//post-end
		
		//not post
		$category_id = $this->request->param('category_id');
		$token = $this->request->token('__token__', 'sha1');
		
		if( $category_id==0 ){
			return ['code'=>0,'msg'=>"error."];exit(0);
		}
		
		//读取分类信息
		$model_MagazineCategory = new MagazineCategory();
		$array_category = $model_MagazineCategory->Find($category_id);
		
		//默认插入的分类
		$array_default_catalogue = [
			"0"=>["name"=>"头条","cid"=>1,"checked"=>"checked"],
			"1"=>["name"=>"精选","cid"=>2,"checked"=>"checked"],
			"2"=>["name"=>"知证","cid"=>3,"checked"=>"checked"],
			"3"=>["name"=>"轻松","cid"=>4,"checked"=>"checked"],
			
		];
		$this->data = array_merge($this->data,[
				'token'=>$token,
				'array_category'=>$array_category,
				'array_default_catalogue'=>$array_default_catalogue,
				
				]);
		return [MAGAZINE_VIEW_PATH.'index/add.php',array_merge($this->data)];
	}
	
	/**
	 * @desc  更新期刊
	 */
	public function Edit(){
	
		//post start
		if($this->request->isPost()){
				
			$post_datas = $this->request->post();
			$post_data = $post_datas['info'];
				
			//更新主表
			$update_magazin = $this->_DealMagazine($post_data);
			if(!isset($update_magazin['id'])){
				return ['code'=>0,'msg'=>$update_magazin];exit(0);
			}
				
			//更新目录
			$model_MagazineCatalogue = new MagazineCatalogue();
			$where_MagazineCatalogue = [
			'magazine_id'=>$post_data['id']
			];
			$array_MagazineCatalogue = $model_MagazineCatalogue->all($where_MagazineCatalogue)->toArray();
			if(!isset($post_data['catalogue'])){
				$post_data['catalogue']=[];
				$array_post_data=[];
			}
			foreach ($post_data['catalogue'] as $key=>$value){
				$arr = explode('|', $value);
				$array_post_data[] = $arr[0];
			}
				
			//即使不选择  只修改状态->0
			foreach ($array_MagazineCatalogue as $key=>$value){
				if(in_array($value['cid'], $array_post_data)){
					$array_MagazineCatalogue[$key]['status'] = 1;
				}else{
					$array_MagazineCatalogue[$key]['status'] = 0;
				}
			}
				
			$result_update_MagazineCatalogue = $model_MagazineCatalogue->Add($array_MagazineCatalogue);
			if($result_update_MagazineCatalogue){
				return ['code'=>1,'msg'=>'更新成功.','url'=>url('content/lists',['id'=>$post_data['category_id']])];exit(0);
			}
				
			return ['code'=>0,'msg'=>"error.请联系管理员."];exit(0);
		}//post end
	
		//not post
		$id = $this->request->param('id');
		$id = intval($id);
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		$token = $this->request->token('__token__', 'sha1');
		if($id==0 || $category_id==0){
			return ['code'=>0,'msg'=>"error."];exit(0);
		}
	
		//主表信息
		$model_Magazine = new Magazine();
		$where_Magazine = [
		'id'=>$id
		];
		$array_Magazine = $model_Magazine->get($where_Magazine,[])->toArray();
	
		//分类信息
		$model_MagazineCategory = new MagazineCategory();
		$array_category = $model_MagazineCategory->Find($category_id);
	
		//目录信息
		$model_MagazineCatalogue = new MagazineCatalogue();
		$where_MagazineCatalogue = [
		'magazine_id'=>$id
		];
		$array_MagazineCatalogue = $model_MagazineCatalogue->all($where_MagazineCatalogue)->toArray();
		//print_r($array_MagazineCatalogue);
		//默认插入的分类
		$array_default_catalogue = [
		"0"=>["name"=>"头条","cid"=>1,"checked"=>""],
		"1"=>["name"=>"精选","cid"=>2,"checked"=>""],
		"2"=>["name"=>"知证","cid"=>3,"checked"=>""],
		"3"=>["name"=>"轻松","cid"=>4,"checked"=>""],
		];
		foreach ($array_default_catalogue as $key=>$value){
			foreach ($array_MagazineCatalogue as $k=>$val){
				if( $value['cid']==$val['cid'] &&  $val['status']){
					$array_default_catalogue[$key]["checked"] = "checked";
				}
			}
		}
	
		//print_r($array_Magazine);
	
		$array_catalogue = [];
		$this->data = array_merge($this->data,[
				'id'=>$id,
				'token'=>$token,
				'array_category'=>$array_category,
				'array_catalogue'=>$array_default_catalogue,
				'data'=>$array_Magazine
				]);
		return [MAGAZINE_VIEW_PATH.'index/edit.php',array_merge($this->data)];
	}
	
	/**
	 * @desc   处理magazine主表信息
	 * @param  array $var 
	 * @return Ambigous <multitype:, \think\model\Collection, \think\Collection, \magazine\model\Ambigous, boolean, \think\false, multitype:Ambigous <\think\$this, \magazine\model\Magazine> >|boolean
	 */
	private function _DealMagazine($var){
		if(is_array($var)){
			$model_Magazine = new Magazine();
			$data_Magazine = [
			'id'=> isset($var['id'])?$var['id']:null,
			'title'=>$var['title'],
			'thumb'=>$var['thumb'],
			'desc'=>isset($var['desc'])?$var['desc']:'',
			'tags'=>isset($var['tags'])?$var['tags']:'',
			'category_id'=>$var['category_id'],
			'gourl'=> isset($var['gourl'])?$var['gourl']:'',
			'uid'=>$this->uid,
			'username'=>$this->username
			];
			$insertInfo = $model_Magazine->Add($data_Magazine);
			if($insertInfo){
				$insertInfo = collection($insertInfo)->toArray();
				$res = $insertInfo[0];
			}else{
				$res = $model_Magazine->getError();
			}
			return $res;
		}
		return false;
	}
	
	/**
	 * 
	 * @param 主表数据id $magazine_id
	 * @param 分类id $category_id
	 * @param 目录数据 1|xx $var
	 * @return Ambigous <multitype:, \think\model\Collection, \think\Collection, \magazine\model\Ambigous, boolean, \think\false, multitype:Ambigous <\think\$this, \magazine\model\MagazineCatalogue> >
	 */
	private function _DealCatalogue($magazine_id,$category_id,$var){
		if(is_array($var)){
			foreach ($var as $key=>$value){
				$arr = explode('|', $value);
				$data[$key] = [
					'cid'=>$arr[0],
					'name'=>$arr[1],
					'magazine_id'=>$magazine_id,
					'category_id'=>$category_id
				];
			}
			//print_r($data);
			$model_MagazineCatalogue = new MagazineCatalogue();
			$insertInfo = $model_MagazineCatalogue->Add($data);
			if($insertInfo){
				$insertInfo = collection($insertInfo)->toArray();
				$res = $insertInfo[0];
			}else{
				$res = $insertInfo;
			}
			return $res;
		}
	}
	
	/**
	 * 
	 * @param array $var
	 * @return Ambigous <string, multitype:>|boolean
	 */
	private function _DealMagazineArticle($var){
		if(is_array($var)){
			$model_MagazineArticle = new MagazineArticle();
			$data_MagazineArticle = [
			'title'=>$var['title'],
			'thumb'=>$var['thumb'],
			'desc'=>isset($var['desc'])?$var['desc']:'',
			'tags'=>isset($var['tags'])?$var['tags']:'',
			'category_id'=>intval($var['category_id']),
			'catalogue_id'=>intval($var['catalogue_id']),
			'magazine_id'=>intval($var['magazine_id']),
			'gourl'=> isset($var['gourl'])?$var['gourl']:'',
			'uid'=>$this->uid,
			'username'=>$this->username,
			'body'	=>isset($var['body'])?$var['body']:'',
			];
			$insertInfo = $model_MagazineArticle->Add($data_MagazineArticle);
			if($insertInfo){
				$insertInfo = collection($insertInfo)->toArray();
				$res = $insertInfo[0];
			}else{
				$res = $model_MagazineArticle->getError();
			}
			return $res;
		}
		return false;
	}
	
	/**
	 * @desc   期刊审核
	 * @return multitype:number string
	 */
	public function Check(){
		$id = $this->request->param('id');
		$id = intval($id);
		$status = $this->request->param('status');
		$status = intval($status);
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		if($id){
			$model_Magazine = new Magazine();
			if($status){
				$update_data_Magazine = [
				'status' => 0
				];
			}else{
				$update_data_Magazine = [
				'status' => 1
				];
			}

			$where = [
				'id'=>$id
			];
			$model_Magazine->save($update_data_Magazine,$where);
			
			return ['code'=>1,'msg'=>"操作成功.",'url'=>url('lists',['id'=>$category_id])];
		}else{
			return ['code'=>0,'msg'=>"id error.",'url'=>url('lists',['id'=>$category_id])];
			exit(0);
		}
	}
	
	/**
	 * @desc   期刊回收站
	 * @return multitype:string multitype:
	 */
	public function Recycle(){
		//分类id
		$category_id = $this->request->param('id');
		$category_id = intval($category_id);
		$model_Magzine = new Magazine();
		$resultSet_Magzine = $model_Magzine->Lists($category_id,20,-1);
		if($resultSet_Magzine['list']){
			$resultSet_Magzine['list'] = collection($resultSet_Magzine['list'])->toArray();
		}
		//print_r($resultSet_Magzine['list']);
		$template_data = array_merge(
				$this->data,
				['category_id'=>$category_id],
				['list'=>$resultSet_Magzine['list']]
		);
		return [MAGAZINE_VIEW_PATH.'index/recycle.php',$template_data];
	
	}
	
	/**
	 * @desc   恢复期刊 + 恢复期刊内容  
	 * @return multitype:number string
	 */
	public function Recovery(){
		$id = $this->request->param('id');
		$id = intval($id);
		$status = $this->request->param('status');
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		if($id){
			$model_Magazine = new Magazine();
			if($status=='R'){
				$update_data_Magazine = [
				'status' => '0'
						];
			}else{
				$update_data_Magazine = [
				'status' => 0
				];
			}
		
			$where = [
			'id'=>$id
			];
				
			$result_Magazine = $model_Magazine->save($update_data_Magazine,$where);
				
			//恢复期刊   文章内容
			if($result_Magazine){
		
				$model_MagazineArticle = new MagazineArticle();
				$update_data_MagazineArticle = [
				'status' => '0'
						];
				$where_MagazineArticle =[
				'magazine_id'=>$id
				];
				$model_MagazineArticle->save($update_data_MagazineArticle,$where_MagazineArticle);
		
			}	
		
			return ['code'=>1,'msg'=>"操作成功.",'url'=>url('Recycle',['id'=>$category_id])];
		}else{
			return ['code'=>0,'msg'=>"id error.",'url'=>url('Recycle',['id'=>$category_id])];
			exit(0);
		}
	}
	
	/**
	 * @desc   删除期刊 + 删除期刊内容   [备注:不是真正删除，而是改变状态 -1]
	 * @return multitype:number string
	 */
	public function Delete(){
		
		$id = $this->request->param('id');
		$id = intval($id);
		$status = $this->request->param('status');
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		if($id){
			$model_Magazine = new Magazine();
			if($status=='D'){
				$update_data_Magazine = [
				'status' => '-1'
				];
			}else{
				$update_data_Magazine = [
				'status' => 0
				];
			}
		
			$where = [
			'id'=>$id
			];
			
			$result_Magazine = $model_Magazine->save($update_data_Magazine,$where);
			
			//删除期刊  文章内容
			if($result_Magazine){
				
				$model_MagazineArticle = new MagazineArticle();
				$update_data_MagazineArticle = [
					'status' => '-1'
					];
				$where_MagazineArticle =[
					'magazine_id'=>$id
				];
				$model_MagazineArticle->save($update_data_MagazineArticle,$where_MagazineArticle);
				
			}
			
				
			return ['code'=>1,'msg'=>"操作成功.",'url'=>url('lists',['id'=>$category_id])];
		}else{
			return ['code'=>0,'msg'=>"id error.",'url'=>url('lists',['id'=>$category_id])];
			exit(0);
		}
	}
	
	
	
	/**
	 * @desc   期刊内容列表
	 * @return multitype:string multitype:
	 */
	public function ListSub(){
		$magazine_id = $this->request->param('magazine_id');//magazine_id
		$magazine_id = intval($magazine_id);
		$catalogue_id = $this->request->param('catalogue_id');
		$catalogue_id = intval($catalogue_id);
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		//默认目录
		$array_default_catalogue = [
		"0"=>["name"=>"头条","cid"=>1,"checked"=>"checked"],
		"1"=>["name"=>"精选","cid"=>2,"checked"=>"checked"],
		"2"=>["name"=>"知证","cid"=>3,"checked"=>"checked"],
		"3"=>["name"=>"轻松","cid"=>4,"checked"=>"checked"],
		];
	
		//主表列表
		$model_MagazineArticle = new MagazineArticle();
		$collection_MagazineArticle = $model_MagazineArticle->Lists($magazine_id,$catalogue_id);
	
	
		$template_data = array_merge(
				$this->data,
				['magazine_id'=>$magazine_id],
				['category_id'=>$category_id],
				['array_default_catalogue'=>$array_default_catalogue],
				['catalogue_id'=>$catalogue_id],
				['list'=>$collection_MagazineArticle['list']],
				['page'=>$collection_MagazineArticle['page']]
		);
		return [MAGAZINE_VIEW_PATH.'index/listsub.php',$template_data];
	}
	
	/**
	 * @desc   添加期刊内容，根据目录添加
	 * @return multitype:number Ambigous <\magazine\controller\Ambigous, boolean, string, multitype:> |multitype:number string Ambigous <\magazine\model\Ambigous, boolean, multitype:, \think\false, multitype:Ambigous <\think\$this, \magazine\model\MagazineArticleStats> > |multitype:number Ambigous <string, multitype:> |multitype:number string |multitype:string multitype:
	 */
	public function AddSub(){
	
		//post 数据处理
		if($this->request->isPost()){
			$post_datas = $this->request->post();
			$post_data = $post_datas['info'];
			$post_data_body = $this->request->post('editorValue','','htmlspecialchars');
			$post_data['body'] = isset($post_data_body) ? $post_data_body : '';
			//print_r($post_data);
			//插入主表
			$insert_MagazineArticle = $this->_DealMagazineArticle($post_data);
			if(!isset($insert_MagazineArticle['id'])){
				return ['code'=>0,'msg'=>$insert_MagazineArticle];exit(0);
			}
				
			if($insert_MagazineArticle['id'] &&  $post_data){
	
				//更新缩略图片各id
				$model_MagazineUpload = new MagazineUpload();
				$update_data_MagazineUpload = [
				'had'=>1,
				'magazine_id'=>$post_data['magazine_id'],
				'magazine_article_id'=>$insert_MagazineArticle['id'],
				'category_id'=>$post_data['category_id'],
				'catalogue_id'=>$post_data['catalogue_id']
				];
				$update_where_MagazineUpload = [
				'token'=>$post_datas['__token__'],
				];
				$model_MagazineUpload->update($update_data_MagazineUpload,$update_where_MagazineUpload);
	
				//更新ueditor数据表
				$update_data_MagazineUeditor = [
				'magazine_article_id'=>$insert_MagazineArticle['id'],
				];
				$model_MagazineUeditor = new MagazineUeditor();
				$model_MagazineUeditor->update($update_data_MagazineUeditor,$update_where_MagazineUpload);
	
				//插入统计表
				$model_MagazineArticleStats = new MagazineArticleStats();
				$insert_magazinArticleStats = [
				'magazine_id'=>$post_data['magazine_id'],
				'magazine_article_id'=>$insert_MagazineArticle['id'],
				'category_id'=>$post_data['category_id'],
				'catalogue_id'=>$post_data['catalogue_id']
				];
				$res_insert_magazinArticleStats = $model_MagazineArticleStats->Add($insert_magazinArticleStats);
	
				if($res_insert_magazinArticleStats){
					return ['code'=>1,'msg'=>$res_insert_magazinArticleStats,'url'=>url('content/lists',['id'=>$post_data['category_id']])];exit(0);
				}else{
					return ['code'=>0,'msg'=>$model_MagazineArticleStats->getError(),];exit(0);
				}
	
			}
				
			return ['code'=>0,'msg'=>"error."];exit(0);
		}//post end
	
	
		$magazine_id = $this->request->param('magazine_id');
		$magazine_id = intval($magazine_id);
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		$token = $this->request->token('__token__', 'sha1');
	
		if($magazine_id==0 || $category_id==0 ){
			return ['code'=>0,'msg'=>"error."];exit(0);
		}
	
		//获取分类
		$model_MagazineCategory = new MagazineCategory();
		$array_category = $model_MagazineCategory->Find($category_id);
		//print_r($array_category);
	
		//获取目录
		$model_MagazineCatologue = new MagazineCatalogue();
		$where_MagazineCatologue = [
		'magazine_id'=>$magazine_id
		];
		$collection_MagazineCatologue = $model_MagazineCatologue::all($where_MagazineCatologue);
		$array_MagazineCatologue = $collection_MagazineCatologue->toArray();
		$string_options_MagazineCatologue = self::Tree($array_MagazineCatologue,0);
		//print_r($array_MagazineCatologue);
	
		$template_data = array_merge(
				$this->data,
				['id'=>$magazine_id],
				['token'=>$token],
				['array_category'=>$array_category],
				['data'=>$array_MagazineCatologue],
				['string_options_catalogue'=>$string_options_MagazineCatologue]
		);
		return [MAGAZINE_VIEW_PATH.'index/addsub.php',$template_data];
	}
	
	/**
	 * @desc   修改期刊 文章内容
	 * @return multitype:number Ambigous <string, multitype:> |multitype:number string |multitype:string multitype:
	 */
	public function EditSub(){
		//post start
		if($this->request->isPost()){
			//数据处理
			$post_datas = $this->request->post();
			$post_data = $post_datas['info'];
			$post_data_body = $this->request->post('editorValue','','htmlspecialchars');
			$post_data['body'] = isset($post_data_body) ? $post_data_body : '';
				
			//更新主表信息
			$model_MagazineArticle = new MagazineArticle();
			$result_update_MagazineArticle = $model_MagazineArticle->update($post_data);
			if(!$result_update_MagazineArticle){
				return ['code'=>0,'msg'=>$model_MagazineArticle->getError()];exit(0);
			}
				
			//更新统计表信息 - catalogue_id
			$model_MagazineArticleStats = new MagazineArticleStats();
			$data_MagazineArticleStats = [
			'catalogue_id'=>$post_data['catalogue_id']
			];
			$where_MagazineArticleStats = [
			'magazine_article_id'=>$post_data['id']
			];
			$result_update_MagazineArticleStats = $model_MagazineArticleStats -> update($data_MagazineArticleStats,$where_MagazineArticleStats);
			if(!$result_update_MagazineArticleStats){
				return ['code'=>0,'msg'=>$model_MagazineArticleStats->getError()];exit(0);
			}
				
			if($post_data['thumb']){
				//更新缩略图信息- catalogue_id    考虑之前没有缩略图的情况
				$model_MagazineUpload = new MagazineUpload();
				$data_MagazineUpload = [
				'catalogue_id'=>$post_data['catalogue_id'],
				'had'=>1,
				'magazine_id'=>$post_data['magazine_id'],
				'magazine_article_id'=>$post_data['id'],
				'category_id'=>$post_data['category_id']
				];
				$where_MagazineUpload = [
				'magazine_article_id'=>$post_data['id']
				];
				$where_MagazineUpload2 = [
				'token'=>$post_datas['__token__']
				];
	
				$result_update_MagazineUpload = $model_MagazineUpload->save($data_MagazineUpload,$where_MagazineUpload);
				//print_r($result_update_MagazineUpload);
				if(!$result_update_MagazineUpload){
					$result_update_MagazineUpload = $model_MagazineUpload->update($data_MagazineUpload,$where_MagazineUpload2);
					//print_r($result_update_MagazineUpload);
				}
				if(!$result_update_MagazineUpload){
					return ['code'=>0,'msg'=>$model_MagazineUpload->getError()];exit(0);
				}
			}
				
			return ['code'=>1,'msg'=>"更新成功.",'url'=>url('listsub',['magazine_id'=>$post_data['magazine_id'],'category_id'=>$post_data['category_id']])];exit(0);
		}//post end
	
		//not post
		$id = $this->request->param('id');
		$id = intval($id);
	
		$token = $this->request->token('__token__', 'sha1');
		if($id==0 ){
			return ['code'=>0,'msg'=>"error."];exit(0);
		}
	
		//获取主表信息
		$model_MagazineArticle = new MagazineArticle();
		$where_MagazineArticle = [
		'id'=>$id
		];
		$array_MagazineArticle = $model_MagazineArticle->get($where_MagazineArticle)->toArray();
	
		//获取分类
		$model_MagazineCategory = new MagazineCategory();
		$array_category = $model_MagazineCategory->Find($array_MagazineArticle['category_id']);
	
		//获取目录
		$model_MagazineCatologue = new MagazineCatalogue();
		$where_MagazineCatologue = [
		'magazine_id'=>$array_MagazineArticle['magazine_id']
		];
		$collection_MagazineCatologue = $model_MagazineCatologue::all($where_MagazineCatologue);
		$array_MagazineCatologue = $collection_MagazineCatologue->toArray();
		$string_options_MagazineCatologue = self::Tree($array_MagazineCatologue,$array_MagazineArticle['catalogue_id'],'cid');
		$template_data = array_merge(
				$this->data,
				['id'=>$id],
				['token'=>$token],
				['array_category'=>$array_category],
				['array_magazine_article'=>$array_MagazineArticle],
				['string_options_catalogue'=>$string_options_MagazineCatologue]
		);
		return [MAGAZINE_VIEW_PATH.'index/editsub.php',$template_data];
	}
	
	/**
	 * @desc   审核期刊内容
	 * @return multitype:number string
	 */
	public function CheckSub(){
		$id = $this->request->param('id');
		$id = intval($id);
		$status = $this->request->param('status');
		$status = intval($status);
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		$magazine_id = $this->request->param('magazine_id');
		$magazine_id = intval($magazine_id);
		if($id){
			$model_MagazineArticle = new MagazineArticle();
			if($status){
				$update_data_MagazineArticle = [
				'status' => 0
				];
			}else{
				$update_data_MagazineArticle = [
				'status' => 1
				];
			}
	
			$where = [
			'id'=>$id
			];
			$model_MagazineArticle->save($update_data_MagazineArticle,$where);
			return ['code'=>1,'msg'=>"操作成功.",'url'=>url('listsub',['magazine_id'=>$magazine_id,'category_id'=>$category_id])];
		}else{
			return ['code'=>0,'msg'=>"id error.",'url'=>url('listsub',['category_id'=>$category_id])];
			exit(0);
		}
	}
	
	/**
	 * @desc   删除周刊内容
	 * @return multitype:number string
	 */
	public function DeleteSub(){
	
		$id = $this->request->param('id');
		$id = intval($id);
		$status = $this->request->param('status');
		$category_id = $this->request->param('category_id');
		$category_id = intval($category_id);
		$magazine_id = $this->request->param('magazine_id');
		$magazine_id = intval($magazine_id);
		if($id){
			$model_MagazineArticle = new MagazineArticle();
			if($status=='D'){
				$update_data_MagazineArticle = [
				'status' => '-1'
						];
			}else{
				$update_data_MagazineArticle = [
				'status' => 0
				];
			}
				
			$where = [
			'id'=>$id
			];
			$result_MagazineArticle = $model_MagazineArticle->save($update_data_MagazineArticle,$where);
				
			return ['code'=>1,'msg'=>"操作成功.",'url'=>url('listsub',['magazine_id'=>$magazine_id,'category_id'=>$category_id])];
		}else{
			return ['code'=>0,'msg'=>"id error.",'url'=>url('listsub',['magazine_id'=>$magazine_id,'category_id'=>$category_id])];
			exit(0);
		}
	}
	
	/**
	 * @desc   上传缩略图 OR 更新缩略图
	 * @return multitype:number Ambigous <> Ambigous <\think\mixed, string> |multitype:number multitype: Ambigous <> |multitype:number NULL Ambigous <\think\mixed, string>
	 */
	public function UploadThumb(){
		// 获取表单上传文件 例如上传了001.jpg
		$file = request()->file('file');
		
		//设置验证规则
		$rule =  Config::get("upload",'magazine');
		//dump($rule);
		$file->validate($rule);
		
		//print_r($file->getInfo());
		$fileinfo = $file->getInfo();
		// 移动到框架应用根目录/public/uploads/ 目录下
		$upload_dir = ROOT_PATH . 'public' . DS . 'uploads'. DS . 'magazine'. DS . $this->uid;
		if(!is_dir($upload_dir)){
			mkdir($upload_dir);
		}
		//dump($fileinfo);
		
		$info = $file->move($upload_dir,true,false);
		if($info){
			// 成功上传后 获取上传信息
				
			$res = [
			'filename'=>$info->getFilename(),
			'savename'=>$info->getSaveName(),
			'filetype'=>$info->getExtension(),
			'filetype2'=>$fileinfo['type'],
			'filesize'=>$fileinfo['size'],
			'name'=>$fileinfo['name'],
			];
				
			//插入数据库进行管理
			$data = $res;
			$data['path'] 	= str_replace('\\','/',str_replace(ROOT_PATH. 'public', '', $upload_dir)).'/'.str_replace('\\', '/', $data['savename']);
			$data['uid'] 	= $this->uid;
			$data['token'] 	= $this->request->param('token');
			
			//判断是更新还是上传
			$oldfile = $this->request->param('oldfile');
			$oldfile = urldecode($oldfile);
			$id = $this->request->param('magazine_id');
			$id = intval($id);
			if($oldfile && $id){
				//更新
				$model_MagazineUpload = new MagazineUpload();
				$where_MagazineUpload = [
					'path'=>$oldfile
				];
				$update_data_MagazineUpload = $data;
				$result = $model_MagazineUpload->update($update_data_MagazineUpload,$where_MagazineUpload);
				
				if($result){
					
					//删除 旧图片
					if(is_file(ROOT_PATH .$oldfile)){
						@unlink(ROOT_PATH .$oldfile);
					}
					//更新主表缩略图
					$model_Magazine = new Magazine();
					$where_Magazine = [
						'id'=>$id
					];
					$update_data_Magazine = [
						'thumb'=>$data['path']
					];
					$result = $model_Magazine->update($update_data_Magazine,$where_Magazine);
					if(!$result){
						return ['code'=>0,'msg'=>$file->getError()."|modelerror:".$model_Magazine->getError(),'status'=>0,'error'=>$result];
					}
					
				}else{
					
					return ['code'=>0,'msg'=>$file->getError()."|modelerror:".$model_MagazineUpload->getError(),'status'=>0,'error'=>$result];
				}
				
				
			}else{
				
				//新增
				//print_r($data);
				$model_MagazineUpload = new MagazineUpload();
				$result = $model_MagazineUpload->Add($data);
				if(!$result['status']){
					return ['code'=>0,'msg'=>$file->getError(),'status'=>0,'error'=>$result['info']];
				}
				
			}
			
			return ['code'=>1,'msg'=>$result,'status'=>1,'data'=>$data,'info'=>$result];
		}else{
		
			// 上传失败获取错误信息
			return ['code'=>0,'msg'=>$file->getError(),'status'=>0,'error'=>$file->getError()];
				
		}
	}
	


	
}