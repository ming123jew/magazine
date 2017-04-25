<?php
namespace magazine\validate;
use think\Validate;
class MagazineArticleStats extends Validate
{
	protected $rule = [
		'magazine_id'  =>  'require',
		'category_id'  =>  'require',
		'catalogue_id'=>'require',
		'magazine_article_id'=>'require',
	];

	protected $message = [
		'magazine_id.require'  =>  'magazine_id必须',
		'category_id.require'  =>  'category_id必须',
		'catalogue_id.require'  =>  'catalogue_id必须',
		'magazine_article_id.require'  =>  'magazine_article_id必须'
	];

	protected $scene = [
		'add'   =>  ['magazine_id','category_id','catalogue_id','magazine_article_id']
	];
}