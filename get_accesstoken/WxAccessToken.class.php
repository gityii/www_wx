<?php

    class WxAccessToken
	{
		private $access_token;
		private $appid;
		private $appsecret;
		
		public function __construct($appid, $appsecret)
		{
			if(!$appid || !$appsecret)
			{
				exit('param error!');
			}
            $this->appid = $appid;
            $this->appsecret = $appsecret;			
		}
		/*
		 * 被注释掉的部分执行不能成功，可能跟file_get_contents有关，这个函数是执行文件操作的
		private function getAccessTokenData()
		{
			$url = 'https://api.weixin.qq.com/cig-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;
			echo $url;
			return json_decode(file_get_contents($url), true);
		}

		public function getAccessToken()
		{
			$a_t_data = $this->getAccessTokenData();
			echo 'a_t_data: ';
			print_r($a_t_data);
			return $a_t_data['acess_token'];
		}
*/


		private function getAccessTokenData()
		{
            $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid='.$this->appid.'&secret='.$this->appsecret;

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $output = curl_exec($ch);
            curl_close($ch);
            $jsoninfo = json_decode($output, true);
            return $jsoninfo;
		}



        public function getAccessToken()
        {
            $jsoninfo = $this->getAccessTokenData();
            $access_token = $jsoninfo["access_token"];
            return $access_token;
        }


    // 本地文件方式：获取最新的access_token
    public function getAccessTokenByFile()
    {
        // 读取本地的json文件
        $file_data = file_get_contents('./access_token.json');

        if(!$file_data)
        {
            // 获取最新的access_token
            $a_t_token = $this->getAccessTokenData();
            print_r($a_t_token);
            $a_t_token['create_time'] = time();
            file_put_contents('./access_token.json' ,json_encode($a_t_token));
            // 返回结果集
            echo ' access_token: '.$a_t_token['access_token'];
            return $a_t_token['access_token'];
        }
        else
        {
            // 转换为数组格式
            $file_data_arr = json_decode($file_data , true);
            // 判断token是否过期
            if((time() - $file_data_arr['create_time']) > 3600)
            {
                // 获取最新的access_token
                $a_t_token = $this->getAccessTokenData();
                $a_t_token['create_time'] = time();
                file_put_contents('./access_token.json' ,json_encode($a_t_token));
                // 返回结果集
                return $a_t_token['access_token'];
            }
            else
            {
                //　直接返回数据库中的结果
                return $file_data_arr['access_token'];
            }
        }
    }


    // 数据库方式：获取最新的access_token
    public function getAccessTokenByDb()
    {
        $conn = Db::getMySQLconnect(MYSQL_HOST , MYSQL_USER , MYSQL_PWD);
        $conn->selectDb('wxtoken');

        // 获取最后一条数据
        $sql = "select * from db_access_token order by id desc";
        $re = $conn->query($sql);
        $res =  $conn->fetch_array($re);
        if(!$res)
        {
            // 获取最新的access_token
            $a_t_token = $this->getAccessTokenData();
            // 写入数据库
            $sql = "insert into db_access_token values ( null , '".$a_t_token['access_token']."' , ".time()." , '".date('Y-m-d')."' , 1)";
            $conn->query($sql);
            // 返回结果集
            return $a_t_token['access_token'];
        }
        else
        {
            // 判断token是否过期
            if((time() - $res['create_time']) > 3600)
            {
                // 获取最新的access_token
                $a_t_token = $this->getAccessTokenData();
                // 写入数据库
                $sql = "insert into db_access_token values ( null , '".$a_t_token['access_token']."' , ".time()." , '".date('Y-m-d')."' , 1)";
                $conn->query($sql);
                // 返回结果集
                return $a_t_token['access_token'];
            }
            else
            {
                //　直接返回数据库中的结果
                return $res['access_token'];
            }
        }
    }


}



	
	
	