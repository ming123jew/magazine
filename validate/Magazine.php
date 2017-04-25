<?php
namespace magazine\validate;
use think\Validate;
class Magazine extends Validate
{
	protected $rule = [
		'title'  =>  'require',
		'category_id'	=>	'require',
		'uid'	=> 'require',
	];

	protected $message = [
		'title.require'  =>  '标题名称必须',
		'category_id.require'  =>  'category_id必须',
		'uid.require'  =>  'uid必须',
	];

	protected $scene = [
		'add'   =>  ['title','category_id','uid'],
		'edit'  =>  ['title','category_id','uid'],
	];
}