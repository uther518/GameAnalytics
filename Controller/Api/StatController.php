<?php
if( !defined( 'IN_INU' ) )
{
	return;
}

/**
 * 统计接口API
 * @author liuchangbing
 * 公共字段
 * $uid
 * $serverName
 * $downRefer
 * $ip  
 * 
 */
class StatController extends BaseController
{
	
	/**
	 * 设置初始化环境
	 * $coinName 充值币名称
	 * $goldName 游戏币名称
	 * $maxNewbieStep 新手引导最大步骤数
	**/
	public function init()
	{
		$params = $_GET;
		Stats_Model::initApp( $params );
	}
	
	/**
	 * 新增注册用户
	 * @param unknown $uid 用户游戏内唯一ID
	 * @param unknown $serverName 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param number $level		      初始等级
	 * @param string $ip		  IP地址
	 */
	public function newUser()
	{
		$params = $_GET;
		Stats_Model::newUser( $params );
	}
	
	

	/**
	 * 用户登录统计
	 * @param unknown $uid 用户ID
	 * @param unknown $serverName 平台区服名称，如官网一区
	 * @param unknown $downRefer  下载渠道名称
	 * @param unknown $level	     初始等级
	 * @param unknown $ip         IP地址
	 * @param unknown $registerTime 注册时间
	 * 
	 */
	public function userLogin()
	{
		$params = $_GET;
		Stats_Model::userLogin( $params );
	}
	
	
	

	/**
	 * 冲值统计
	 * @param unknown $uid 用户ID
	 * @param unknown $orderId 订单ID
	 * @param unknown $rmb	人民币
	 * @param unknown $coin 游戏币
	 */
	public function recharge()
	{
		$params = $_GET;
		Stats_Model::recharge( $params );
	}
	
	
	
	
	/**
	 * 在线用户统计，每五分钟，统计一次，8:35  8:40  8:45 8:50 
	 */
	public function userOnline()
	{
		$params = $_GET;
		Stats_Model::userOnline( $params );
	}
	


	/**
	 * 获取冲值币记录
	 * @param unknown $uid
	 * @param unknown $gid
	 * @param unknown $gnum
	 * @param unknown $singleCoin
	 * @param unknown $totalCoin
	 * @param unknown $evtDesc
	 */
	public function gainCoin()
	{
		$params = $_GET;
		Stats_Model::gainCoin( $params );
	}

	
	/**
	 * 消费冲值币记录
	 * @param unknown $uid
	 * @param unknown $gid
	 * @param unknown $gnum
	 * @param unknown $singleCoin
	 * @param unknown $totalCoin
	 * @param unknown $evtDesc
	 */
	public function consumeCoin()
	{
		$params = $_GET;
		Stats_Model::consumeCoin( $params );	
	}
	
	
	
	/**
	 * 获取游戏币(金币)记录
	 * @param unknown $uid 用户ID
	 * @param unknown $gold	金币数量
	 * @param unknown $evtDesc 事件说明(中文简体说明获取原因)
	 */
	public function gainGold()
	{
		$params = $_GET;
		Stats_Model::gainGold( $params );
	}
	
	
	
	/**
	 * 消费游戏币(金币)记录
	 * @param unknown $uid
	 * @param unknown $gid
	 * @param unknown $gnum
	 * @param unknown $singleGold
	 * @param unknown $totalGold
	 * @param unknown $evtDesc
	 */
	public function consumeGold()
	{
		$params = $_GET;
		Stats_Model::consumeGold( $params );
	
	}
	


	/**
	 * 升级统计
	 */
	public function upLevel()
	{
		$params = $_GET;
		Stats_Model::newUser( $params , true );
	}
	

	public function upNewbie()
	{
		$params = $_GET;
        Stats_Model::newUser( $params , true );
	}	

	
	/**
	 * 道具获取记录
	 * @param unknown $uid 用户ID
	 * @param unknown $itemId 道具ID
	 * @param unknown $itemNum	道具数量
	 * @param unknown $itemName 道具名称
	 * @param unknown $evtDesc  获取道具的原因
	 */
	public function gainItem()
	{
		$params = $_GET;
		Stats_Model::gainItem( $params );
	}
	

	/**
	 * 失去获取记录
	 * @param unknown $uid 用户ID
	 * @param unknown $itemId 道具ID
	 * @param unknown $itemNum	道具数量
	 * @param unknown $itemName 道具名称
	 * @param unknown $evtDesc  获取道具的原因
	 */
	public function lostItem()
	{
		$params = $_GET;
		Stats_Model::lostItem( $params );
	}
	
	
	
	
	public function haveCoin()
	{
		$params = $_GET;
		Stats_Model::haveCoin( $params );
	}
	
	
	
	public function haveGold()
	{
		$params = $_GET;
		Stats_Model::haveGold( $params );
	}
	
	
	
	public function playMethod()
	{
		$params = $_GET;
		Stats_Model::playMethod( $params );
	}
	
	
	
	public function customAction()
	{
		$params = $_GET;
		Stats_Model::customAction( $params );
	}
	
	
	public function evtCounter()
	{
		$params = $_GET;
		Stats_Model::evtCounter( $params );
	}
	/**
	 * 接收任务统计
	 */
	public function acceptTask()
	{
		$params = $_GET;
		Stats_Model::acceptTask( $params );
	}
	
	
	/**
	 * 完成任务统计
	 */
	public function finishTask()
	{
		$params = $_GET;
		Stats_Model::finishTask( $params );
	
	}
	
	
	/**
	 * 新手引导统计
	 */
	public function newbieStep()
	{
		$params = $_GET;
		Stats_Model::newbieStep( $params );
	}
	
	
}
