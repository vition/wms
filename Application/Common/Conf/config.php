<?php
$CONFIG=array(
    'DB_TYPE'   => 'mysql',
     'DB_USER'   => (in_array($_SERVER["HTTP_HOST"],['twsh.vitionst.top','twsh.twoway.com.cn','oa.twoway.com.cn']))?'vwms':'root', // 用户名
     'DB_PWD'    => (in_array($_SERVER["HTTP_HOST"],['twsh.vitionst.top','twsh.twoway.com.cn','oa.twoway.com.cn']))?'Vwms2018#23':'root', // 密码
    'DB_PREFIX' => 'v_', // 数据库表前缀 
    'URL_MODEL'=> 2,//伪静态
    // 'DB_HOST' => '47.52.132.90',
    'DB_HOST' => '127.0.0.1',
    'DB_NAME' => 'wmstest', // 数据库名
    'DB_PORT' => 3306, // 端口
    'DB_CHARSET'=> 'utf8', // 字符集
    'DATA_CACHE_TYPE'       => 'Redis',
    'REDIS_HOST'            => '127.0.0.1',
    'REDIS_PORT'            => 6379,
    'DATA_CACHE_TIME'       => 3600,
    'DATA_CACHE_PREFIX'     => 'vwms_',
    'DEFAULT_MODULE'        =>'Admin',
    'URL_CASE_INSENSITIVE'  =>  false,//区分大小写
    //'APP_SUB_DOMAIN_DEPLOY' => 1,
    //'APP_SUB_DOMAIN_RULES' => [
//	'twsh' => 'Admin',
//	'www'  => 'Home'
  //  ],
    'DEFAULT_CONTROLLER'        =>'Index',
    'DEFAULT_ACTION'        =>'Index',
    'TMPL_PARSE_STRING'=>[
        '__ADMINT__'=>__ROOT__.'/Public'.'/admintmpl',
        '__TEMPLATE__'=>__ROOT__.'/Public'.'/template',
        '__UPLOADS__'=>__ROOT__.'/Uploads',
    ],
);

$confiles=scandir(APP_PATH.'Common/Conf/');
foreach ($confiles as $file) {
    $fileArr=explode('.',$file);
    if(count($fileArr)>2 && $fileArr[count($fileArr)-1]=='php' && $fileArr[count($fileArr)-2]=='config'){
        $tempArry = include(APP_PATH.'Common/Conf/'.$file); 
        $CONFIG=array_merge($CONFIG,$tempArry);
    }
}
return $CONFIG;
