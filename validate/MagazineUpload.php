<?php
namespace magazine\validate;

use think\Validate;
class MagazineUpload extends Validate
{
	
	// 定义时间戳字段名
	//protected $createTime = 'create_time';
	//protected $updateTime = 'update_time';
	protected $rule = [
		'filename'  =>  'require',//文件名
		'savename'  =>  'require',//文件名
		'uid'  =>  'require',
		'path'  =>  'require',//路径
		'filesize'=>'require',
		'filetype'=>'require',
		'__token__' => 'token',
		
	];

	protected $message = [
		'filename.require'  =>  'filename必须',
		'savename.require'  =>  'savename必须',
		'uid.require'  =>  'uid必须',
		'path.require'  =>  'path必须',
		'filesize.require'=>'filesize必须',
		'filetype.require'=>'filetype必须',
	];

	protected $scene = [
		'UploadThumb'   =>  ['filename','savename','uid','path','filesize','filetype'],

	];
}