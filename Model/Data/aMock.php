<?php
/**
 * 模拟数据基层（单元测试用）
 * @author Lucky
 */
abstract class Data_aMock
{
	/**
	 * 数据
	 * @var	array
	 */
	protected $data = array();
	
	/**
	 * 设值
	 * @param	string $key	键
	 * @param	mixed $value	值
	 */
	public function set( $key , $value )
	{
		$this->data[$key] = $value;
	}
	
	/**
	 * 获值
	 * @param	string $key	键
	 */
	public function get( $key )
	{
		return $this->data[$key];
	}
}
?>