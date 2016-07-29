<?php

interface iDber
{
	/**
	 * 数据新增接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function add( $tableName , $value , $condition = array() );
	
	/**
	 * 数据修改接口
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function update( $tableName , $value , $condition = array() );
	
	/**
	 * 数据删除接口
	 * @param	string $tableName		数据表名
	 * @param	array $condition		条件:array( 'id' => 1 )
	 * @return	boolean
	 */
	public function delete( $tableName , $condition = array() );
	
	/**
	 * 数据单项查询接口(只能根据用户ID查询)
	 * @param	string $tableName		数据表名
	 * @param	array $value			数据
	 * @return	array
	 */
	public function find( $tableName );
	
	/**
	 * 数据多项查询接口
	 *
	 * @param	string $tableName		数据表名
	 * @param	array $returnItems		需要的字段
	 * @return	array
	 */
	public function findAll( $tableName , $returnItems );
	
	/**
	 * 全局数据ID获取接口
	 *
	 * @param	string $tableName		数据表名
	 * @return	int
	 */
	public function getID( $tableName );
}