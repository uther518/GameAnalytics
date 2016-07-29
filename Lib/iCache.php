<?php

interface iCache
{
	/**
	 * Memcache服务器连接格式
	 * @param	array(	//新版格式
	 * 				current:array(	//现在使用的Memcache集群配置
	 * 					array(
	 * 						host:string	//Memcache服务器IP
	 * 						port:int	//Memcache服务器端口
	 * 					) ,
	 * 					...
	 * 				) ,
	 * 				old:array(	//上一个版本使用的Memcache集群配置
	 * 					array(
	 * 						host:string	//Memcache服务器IP
	 * 						port:int	//Memcache服务器端口
	 * 					) ,
	 * 					...
	 * 				)
	 * 			)
	 * @param	array(	//旧版格式
	 * 				array(
	 * 					host:string	//Memcache服务器IP
	 * 					port:int	//Memcache服务器端口
	 * 				) ,
	 * 				...
	 * 			)
	 */
	public function __construct( $config );
	
	/**
	 * 设置
	 * @param	string|array $key			键或者多个键值对组合
	 * @param	string $value				值
	 * @param	int $expire					过期时间
	 * @param	int $flag					状态标签
	 * @param	int $server_id				服务器ID
	 * @return	boolean
	 */
	public function set( $key , $value = null , $expire = 0 , $flag = 0 , $server_id = false );
	
	/**
	 * 添加
	 * @param	string|array $key			键或者多个键值对组合
	 * @param	string $value				值
	 * @param	int $expire					过期时间
	 * @param	int $flag					状态标签
	 * @return	boolean
	 */
	public function add( $key , $value , $expire = 0 , $flag = 0 );

	/**
	 * 获取
	 * @param	string|array $key			键或者多个键值对组合
	 * @param	int $server_id				服务器ID
	 * @param	boolean $isOld				是否使用旧配置
	 * @return	mixed
	 */
	public function get( $key , $server_id = false , $isOld = false );

	/**
	 * 自增
	 * @param	string $key				键
	 * @param	int $value				自增量
	 * @return	boolean
	 */
	public function increment( $key , $value );

	/**
	 * 删除
	 * @param	string $key				键
	 * @param	int $time_out			超时时间
	 * @param	int $server_id			服务器ID
	 * @param	boolean $isOld			是否旧配置
	 * @return	boolean
	 */
	public function delete( $key , $time_out = 0 , $server_id = false , $isOld = false );
}
?>