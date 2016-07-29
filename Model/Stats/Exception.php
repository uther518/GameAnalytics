<?php

class Stats_Exception extends GameException
{
	/**
	 * 参数错误
	 * @var unknown
	 */
	const STATUS_PARAM_ERROR = 200;
	
	/**
	 * 不存在这个应用,请先创建应用
	 * @var unknown
	 */
	const NOT_THIS_APPID  = 211;
	
	
}

?>