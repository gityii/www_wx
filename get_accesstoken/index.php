<?php

    define('APP_ID', 'wx18b954225b2360fc');
	define('APP_SECRET', '3b671d066237ad9da0b4bb8c0832b05e');

    // 数据库连接信息
    define('MYSQL_HOST' , 'localhost');
    define('MYSQL_USER' , 'root');
    define('MYSQL_PWD' , 'xamppaddme');

    // 引入数据库连接文件和处理类库
    require_once './Db.php';
    require_once './WxAccessToken.class.php';

	$wx = new WxAccessToken(APP_ID, APP_SECRET);
	echo "<pre>";
//直接刷新获取，每获取一次都是新的
//	print_r("access_token:<br>".$wx->getAccessToken());
// 在数据库中缓存获取
// print_r($wx->getAccessTokenByDb());
// 文件缓存获取
// print_r($wx->getAccessTokenByFile());

//获取微信服务器的IP地址列表//
//$url = 'https://api.weixin.qq.com/cgi-bin/getcallbackip?access_token='.$wx->getAccessTokenByFile();
//$data = json_decode(file_get_contents($url) , true);
//print_r($data);

    // 接口调用归零

    $url = 'https://api.weixin.qq.com/cgi-bin/clear_quota?access_token='.$wx->getAccessTokenByFile();
    $data['appid'] = APP_ID;
    $data = json_decode($wx->post_data($url,json_encode($data)) , true);
    print_r($data);

