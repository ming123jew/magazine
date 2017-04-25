<?php
namespace magazine\validate;
use think\Validate;
class MagazineCatalogue extends Validate
{
	protected $rule = [
		'name'  =>  'require',
		'cid'	=>	'require',
	];

	protected $message = [
		'name.require'  =>  'name必须',
		'cid.require'  =>  'cid必须',
	];

	protected $scene = [
		'add'   =>  ['name','cid'],
		'edit'  =>  ['name','cid'],
	];
}