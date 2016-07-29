<?php
/**
 * 系统配置(运维需要更改的配置)
 */
return array
(
	//cn,tw
	'platform' => 'cn',
	'serverList' => array(
	),
		
	/**
	 * memcache配置
	 * memcache客户端类名（memcache:InuMemcache,libMemcache：InuMemcached）
	 */
	'memcacheClass' => 'InuMemcache' ,
	'memcache' => array
	(
		//游戏数据集群
		'data' => array
		(
			array(	"host" => "127.0.0.1" , "port" => 11211 ) ,
		) ,
		
		//lock集群
		'lock' => array
		(
			array(	"host" => "127.0.0.1" , "port" => 11211 ) ,
		) ,
	) ,
		
	

	/**
	 * mongodb配置
	 */
	'mongoDb' => array(
			'statsDB' => array( "host" => '127.0.0.1:27017', "dbname" => "DataAnalysis" ),
	),
	
	
	/**
	 * 消息队列配置ZeroMQ
	 */
	'zmq' => array(
		
	),
		
		
		
	/**
	 * 执行效率监控配置
	 */
	'xhprof' => array(
		'isOpen' => true ,	//是否使用执行效率检查；true => 使用；false => 不使用
		'logDir' => '/tmp/xhprof' ,
	) ,
);
