<?php
namespace magazine\validate;
use think\Validate;
class MagazineCategory extends Validate
{
	protected $rule = [
		'name'  =>  'require',
	];

	protected $message = [
		'name.require'  =>  '媒体分类名称必须',
	];

	protected $scene = [
		'add'   =>  ['name'],
		'edit'  =>  ['name'],
	];
}