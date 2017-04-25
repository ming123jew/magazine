<?php
namespace magazine\validate;
use think\Validate;
class MagazineArticle extends Validate
{
	protected $rule = [
		'magazine_id'	=>	'require',
		'category_id'	=>	'require',
		'catalogue_id'	=>	'require|gt:0',
		'title'  =>  'require',
		'uid'	=> 'require',
	];

	protected $message = [
		'magazine_id.require'  =>  'magazine_id必须',
		'category_id.require'  =>  'category_id必须',
		'catalogue_id.require'  =>  '请选择目录',//catalogue_id必须
		'catalogue_id.gt'  =>  '请选择目录',//catalogue_id必须
		'title.require'  =>  '请填写标题',//标题名称必须
		'uid.require'  =>  'uid必须',
	];

	protected $scene = [
		'add'   =>  ['magazine_id','category_id','catalogue_id','title','uid'],
		'edit'  =>  ['magazine_id','category_id','catalogue_id','title','uid'],
	];
}