<?php
if(!defined('HOST'))die('not access');
//系统配置文件		
return array(
	'url'		=> '',		//系统URL
	'localurl'	=> '',			//本地系统URL，用于服务器上浏览地址
	'title'		=> '信呼协同办公系统',	//系统默认标题
	'apptitle'	=> '信呼OA',			//APP上或PC客户端上的标题
	'db_host'	=> 'localhost',		//数据库地址
	'db_user'	=> 'root',		//数据库用户名
	'db_pass'	=> 'samyxue8341',		//数据库密码
	'db_base'	=> 'tycrm',		//数据库名称
	'db_engine'	=> 'MyISAM',	//数据库使用引擎
	'perfix'	=> 'tycrm_',	//数据库表名前缀
	'qom'		=> 'xinhu_',		//session、cookie前缀
	'highpass'	=> '',			//超级管理员密码，可用于登录任何帐号
	'db_drive'	=> 'mysqli',	//操作数据库驱动有mysql,mysqli,pdo三种
	'randkey'	=> 'hnrfileavdmuzpsbkgywcqxotj',		//系统随机字符串密钥
	'asynkey'	=> '93e817c8f85326d7e23d9daeef908ab7',	//这是异步任务key
	'openkey'	=> '674996481ce6e733b98b757248919049',	//对外接口openkey
	'updir'		=> 'upload',	//默认上传目录
	'sqllog'	=> false,		//是否记录sql日志保存upload/sqllog下
	'asynsend'	=> false,		//是否异步发送提醒消息，为true需开启服务端
	'editpass'	=> '1',			//用户登录修改密码：0不用修改，1强制用户必须修改
	'install'	=> true			//已安装，不要去掉啊
);