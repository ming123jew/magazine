<?php
namespace magazine\validate;
use think\Validate;
class MagazineStats extends Validate
{
	protected $rule = [
		'magazine_id'  =>  'require',
		'category_id'  =>  'require'
	];

	protected $message = [
		'magazine_id.require'  =>  'magazine_id必须',
		'category_id.require'  =>  'category_id必须'
	];

	protected $scene = [
		'add'   =>  ['magazine_id','category_id']
	];
}