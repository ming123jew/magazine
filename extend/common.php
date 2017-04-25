<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件

/**
 * @desc  根据catalogue_id 返回对应名称
 * @param int $catalogue_id
 * @return string
 */
function getCatalogueName($catalogue_id){
	if(intval($catalogue_id)>0){
		$array_default_catalogue = [
			"0"=>["name"=>"头条","cid"=>1,"checked"=>"checked"],
			"1"=>["name"=>"精选","cid"=>2,"checked"=>"checked"],
			"2"=>["name"=>"知证","cid"=>3,"checked"=>"checked"],
			"3"=>["name"=>"轻松","cid"=>4,"checked"=>"checked"],
		];
		
		foreach ($array_default_catalogue as $key => $value){
			if ($value['cid'] == $catalogue_id){
				$name = $value['name'];
			}else{
				continue;
			}
		}
		if($name){
			return $name;
		}else{
			return false;
		}
		
	}
	return false;
}


function curlPost($url,$post_data){
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	// post数据
	curl_setopt($ch, CURLOPT_POST, 1);
	// post的变量
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
	$output = curl_exec($ch);
	curl_close($ch);
	//打印获得的数据
	return ($output);
}



