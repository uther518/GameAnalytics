<?php
if( !defined( 'IN_INU' ) )
{
    return;
}

/**
 * 玩家充值记录,并非购买记录，购买记录见Data_Order_Buy
 * @name model.php
 * @author liuchangbing
 * @since 2013-1-14
 *
 */
class Data_Order_Model extends Data_Abstract
{
	/**
	 * 单例对象
	 * @var	Data_Order_Model[]
	 */
	protected static $singletonObjects;
	/**
	 * 结构化对象
	 * @param	string $userId	用户ID
	 * @param	boolean $lock	是否加锁（需要写的话一定要加锁）
	 */
	public function __construct( $userId , $lock = false  )
	{
		//主键为uid,trade_no
		$this->dbColumns = array(
			'order' => array(
				'columns' => array(
					 'goodsName' , 'goodsNum' , 'email', 'price' , 'discount' , 
					'totalPrice','buyerId' , 'tradeTime' , 'addTime' , 'platform' , 'status' , 'thirdId'
				) ,
				'isNeedFindAll' => true ,
			) ,
		);
		parent::__construct( $userId , 'order_model' , $lock  );
	}
	
	/**
	 * 获取实例化
	 * @param	int $userId	用户ID
	 * @return	Data_Order_Model
	 */
	public static function & getInstance( $userId , $lock = false  )
	{
		if( !isset( self::$singletonObjects[$userId] ) )
		{
			self::$singletonObjects[$userId] = new self( $userId , $lock  );
		}
		
		if( $lock )
		{
			ObjectStorage::register( self::$singletonObjects[$userId] );
		}
		
		return self::$singletonObjects[$userId];
	}
	
	/**
	 * 格式化保存到数据库的数据
	 * @param	array $table	表名
	 * @param	array $data		数据
	 * @return	array
	 */
	protected function formatToDBData( $table , $data )
	{		
		
		$returnData = array(
			'id' => $data['id'],
			'goodsName' => $data['goodsName'],
			'goodsNum' => $data['goodsNum'],
			'email' => $data['email'],
			'price' => $data['price'],
			'discount' => $data['discount'],
			'totalPrice' => $data['totalPrice'],
			'buyerId' => $data['buyerId'],
			'tradeTime' => $data['tradeTime'],
			'addTime' => $data['addTime'],
			'platform' =>  $data['platform'],
			'status' =>  $data['status'],
			'thirdId' => $data['thirdId'],
		);
		
		return $returnData;
	}
	
	/**
	 * 格式化从数据库查到的数据
	 * @see Data_Abstract::formatFromDBData()
	 */
	protected function formatFromDBData( $table , $data )
	{
		$returnData = array();
		if( !empty( $data ))
		{
			foreach ( $data as $order )
			{
				$returnData[$order[1]] = array(
					'id' => $order[1],
					'goodsName' => $order[2],
					'goodsNum' => $order[3],
					'email' => $order[4],
					'price' => $order[5],
					'discount' => $order[6],
					'totalPrice' => $order[7],
					'buyerId' => $order[8],
					'tradeTime' => $order[9],
					'addTime' => $order[10],
					'platform' =>  $order[11],
					'status' =>  $order[12],
					'thirdId' => $order[13],
				);
			}
		}
		return $returnData;
	}
	
	
	
	protected function emptyDataWhenloadFromDB( $table )
	{	
		return $this->data;
	}
	
	/**
	 * 添加一个订单
	 * @param int $fId
	 */
	public function addOrder( $orderInfo )
	{
		if( !$orderInfo['id'] )
		{
			return false;
		}
		
		if( $this->data[$orderInfo['id']]  )
		{
			return false;
		}
		
		$this->data[$orderInfo['id']] = array(
			'id' => $orderInfo['id'],
			'goodsName' => $orderInfo['goodsName'],
			'goodsNum' => $orderInfo['goodsNum'] ? $orderInfo['goodsNum'] : 0 ,
			'email' => $orderInfo['email'],
			'price' => $orderInfo['price'] ?  $orderInfo['price'] : 0 ,
			'discount' => $orderInfo['discount'] ? $orderInfo['discount'] : 0 ,
			'totalPrice' => $orderInfo['totalPrice'] ? $orderInfo['totalPrice'] : 0,
			'buyerId' => $orderInfo['buyerId'],
			'tradeTime' => $orderInfo['tradeTime'] ? $orderInfo['tradeTime'] : 0,
			'addTime' => $orderInfo['addTime'],
			'platform' =>  $orderInfo['platform'],
			'status' => $orderInfo['status'] ? $orderInfo['status'] : 0 ,
			'thirdId' => $orderInfo['thirdId'],
		);
			
		$this->updateToDb( 'order' , self::DATA_ACTION_ADD , $this->data[$orderInfo['id']] );
		return true;
	}
	
	
	/**
	 * 查询订单装态
	 */
	public function checkOrderStatus( $orderId )
	{
		if( $this->data[$orderId]['id'] == $orderId &&  $this->data[$orderId]['status'] == 1 )
		{
			return 1;
		} 
		
		return 0;
	}
	
	/**
	 * 通过第三方交易号判断订单状态
	 * @param unknown $orderId
	 * @param unknown_type $payType
	 * @return number
	 */
	public function checkOrderStatusForThird( $orderId , $payType )
	{
		$ret = 0;
		foreach( $this->data as $id => $order )
		{
			if( $order['thirdId'] == $orderId && $order['platform'] == $payType && $order['status'] == 1 )
			{
				$ret = 1;
				break;
			}
		}
		return $ret;
	}
	
	/**
	 * 更新订单
	 * @param int $fId
	 */
	public function updateOrder( $orderInfo )
	{
		if( !$orderInfo['id'] )
		{
			return false;
		}
		
		$this->data[$orderInfo['id']]['id'] = $orderInfo['id'];
		//$this->data[$orderInfo['id']]['goodsName'] = $orderInfo['goodsName']; //用自己的
		$this->data[$orderInfo['id']]['goodsNum'] = $orderInfo['goodsNum'];
		$this->data[$orderInfo['id']]['price'] = $orderInfo['price'];
		$this->data[$orderInfo['id']]['discount'] = $orderInfo['discount'];
		
		$this->data[$orderInfo['id']]['totalPrice'] = $orderInfo['totalPrice'];
		$this->data[$orderInfo['id']]['buyerId'] = $orderInfo['buyerId'];
		$this->data[$orderInfo['id']]['tradeTime'] = $orderInfo['tradeTime'];
		$this->data[$orderInfo['id']]['status'] = $orderInfo['status'];
		$this->data[$orderInfo['id']]['thirdId'] = $orderInfo['thirdId'];
		
		$this->updateToDb( 'order' , self::DATA_ACTION_UPDATE , $this->data[$orderInfo['id']] );
		return true;
	}
	
	/**
	 * 获取最早的一笔订单
	 * @return int
	 */
	public function getFirstRechargePrice()
	{
		$price = 0;
		$orderData = Data_Order_Model::getInstance( $this->userId )->getData();
		if( !empty( $orderData ) )
		{
			$minTime = $_SERVER['REQUEST_TIME'];
			
			foreach ( $orderData as $key => $order )
			{
				if( $order['addTime'] < $minTime )
				{
					$minTime =  $order['addTime'] ;
					$price = $order['price'] ;
				}
			}
		}
		return $price;
	}
	
	/**
	 * 获得指定订单
	 * Enter description here ...
	 * @param unknown_type $orderId
	 */
	public function getOrder( $orderId )
	{	
		return $this->data[$orderId];
	}
	
}
