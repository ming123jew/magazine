<?php
return [
	// 数据库类型
	'MAGAZINE_db_config1'=>[
		
		'type'        => 'mysql',//\think\mongo\Connection
		// 数据库连接DSN配置
		'dsn'         => '',
		
		// 服务器地址
		'hostname'    => '192.168.12.252',
		// 数据库名
		'database'    => 'huiz_crm',
		// 数据库用户名
		'username'    => '',
		// 数据库密码
		'password'    => '',
		
		// 数据库连接端口
		'hostport'    => '27017',
		// 数据库连接参数x
		'params'      => [],
		// 数据库编码默认采用utf8
		'charset'     => 'utf8',
		// 数据库表前缀
		'prefix'      => 'tp_',
		// 数据库调试模式
		'debug'          => true,
		// 数据库部署方式:0 集中式(单一服务器),1 分布式(主从服务器)
		'deploy'         => 0,
		// 数据库读写是否分离 主从式有效
		'rw_separate'    => false,
		// 读写分离后 主服务器数量
		'master_num'     => 1,
		// 指定从服务器序号
		'slave_no'       => '',
		// 是否严格检查字段是否存在
		'fields_strict'  => false,
		// 数据集返回类型 array 数组 collection Collection对象
		'resultset_type' => 'collection',
		// 是否自动写入时间戳字段
		'auto_timestamp' => false,
		// 是否需要进行SQL性能分析
		'sql_explain'    => false,
	],
	
	'cache'  => [
		'type'   => 'File',
		'prefix' => '',
		'expire' => 0,
		'path'   => ROOT_PATH . 'runtime/cache/magazine',
	],
	
	//管理员表名称   
	'MAGAZINE_admin_table_name'=>'admin',
	//设置管理员信息表的主键id，如字段为uid则填写uid,如是id则填id,如是其它则填其它
	'MAGAZINE_admin_uid'=>'uid',
	'MAGAZINE_admin_username'=>'nickname',
	//设置管理员信息表的角色字段
	'MAGAZINE_admin_role'=>'role',
	//角色名称
	'MAGAZINE_role_table_name'=>'auth_role',
	//角色表的名称字段
	'MAGAZINE_role_name'=>'name',
	'MAGAZINE_role_id'=>'id',
	//通过session获取管理员信息[必须带上主键值uid|id],请设置标示,注意需要带上前缀
	'MAGAZINE_admin_session'=>'abc_user',
	
	//上传设置
	'upload'=>[
		'size'	=>  52428800,//50mb
		//'type'	=>	'image/jpeg,image/gif,image/png,application/x-MS-bmp',
		'ext'	=>	'jpeg,jpg,gif,png,bmp',
		
	],
	
	//默认过滤
	'default_filter'        =>  'strip_tags,htmlspecialchars',
	
	'wx'	=>	[
		'appId'	=>	'wxb44505ea1c27dca7',
		'appSecret'	=>	'c4a375c4cdffdb157070355acb99f35f',
		//'appId'	=>'wxe124c55946255602',
		//'appSecret'	=>'b06f4ddaeac0ca62b6222f99577cae9d'
	],

]

?>