<?php
if( ! defined( 'IN_INU' ) )
{
	return;
}
class ConfigController extends CliBaseController
{

	public function check()
	{
		$systemConfig = Common::getConfig();
		$toCheckMethods = array( 
			'_checkMemcache' , 
			'_checkDbConfiguration' , 
			'_checkFriendServer' , 
			'_checkTaskServer' , 
			'_checkAccumulatorServer' , 
			'_checkPaymentCenter' , 
			'_checkNotificationServer' , 
			'_checkLootLogicServer' 
		);
		echo "\n\n";
		foreach( $toCheckMethods as $toCheckMethod )
		{
			echo "\nStart to Execute {$toCheckMethod}.\n";
			$this->$toCheckMethod( $systemConfig );
			echo "Executed {$toCheckMethod} check.\n";
		}
	}

	/**
	 * 检查Memcache配置
	 * @param	array $config
	 */
	private function _checkMemcache( $config )
	{
		if( ! class_exists( 'Memcached' ) )
		{
			echo "[ERROR]Please Install PHP Extension - Memcached.\n";
			return;
		}
		if( empty( $config['memcache'] ) )
		{
			echo "[ERROR]Memcache Configuration Error.\n";
			return;
		}
		if( ! isset( $config['memcache']['data'] ) )
		{
			echo "[ERROR]Memcache Configuration Not Have 'data' Column.\n";
			return;
		}
		if( ! isset( $config['memcache']['etc'] ) )
		{
			echo "[ERROR]Memcache Configuration Not Have 'etc' Column.\n";
			return;
		}
		if( ! isset( $config['memcache']['session'] ) )
		{
			echo "[ERROR]Memcache Configuration Not Have 'session' Column.\n";
			return;
		}
		if( $config['memcacheClass'] != 'InuMemcached' )
		{
			echo "[ERROR]Memcache Configuration Not Have 'session' Column.\n";
			return;
		}
		//检查数据缓存
		foreach( $config['memcache'] as $columnKey => $configMemcaches )
		{
			if( empty( $configMemcaches ) )
			{
				echo "[ERROR]Memcache Configuration Not Have '{$columnKey}' Column Configuration.\n";
				continue;
			}
			foreach( $configMemcaches as $configMemcache )
			{
				$cache = new Memcached();
				$cache->addServer( $configMemcache['host'] , $configMemcache['port'] );
				if( $cache->getResultCode() > 0 )
				{
					echo "[ERROR]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
					continue;
				}
				$randKey = mt_rand();
				$randValue = mt_rand();
				$cache->add( $randKey , $randValue );
				if( $cache->getResultCode() > 0 )
				{
					echo "[WARNING]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				$result = $cache->get( $randKey );
				if( $cache->getResultCode() > 0 )
				{
					echo "[WARNING]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				if( $result != $randValue )
				{
					echo "[WARNING]Memcached Error : Get Value and Add Value not Equal @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				$randValue = mt_rand();
				$cache->set( $randKey , $randValue );
				if( $cache->getResultCode() > 0 )
				{
					echo "[WARNING]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				$result = $cache->get( $randKey );
				if( $cache->getResultCode() > 0 )
				{
					echo "[WARNING]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				if( $result != $randValue )
				{
					echo "[WARNING]Memcached Error : Get Value and Set Value not Equal @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
				$cache->delete( $randKey , $randValue );
				if( $cache->getResultCode() > 0 )
				{
					echo "[WARNING]Memcached Error : " . $cache->getResultMessage() . " @{$configMemcache['host']}:{$configMemcache['port']}\n";
				}
			}
		}
	}

	private function _checkDbConfiguration( $config )
	{
		$conn = mysql_connect( 
			$config['mysqlDb']['taskIndex']['host'] . ':' . $config['mysqlDb']['taskIndex']['port'] , 
			$config['mysqlDb']['taskIndex']['user'] , $config['mysqlDb']['taskIndex']['passwd'] );
		if( ! $conn || mysql_errno() > 0 )
		{
			echo "[WARNING]Mysql Connect taskIndex Error, Msessage: " . mysql_error() . "\n";
		}
		$result = mysql_select_db( $config['mysqlDb']['taskIndex']['name'] , $conn );
		if( ! $result || mysql_errno( $conn ) > 0 )
		{
			echo "[WARNING]Mysql using taskIndex Error, Msessage: " . mysql_error( $conn ) . "\n";
		}
		//检查任务定义表结构
		$sql = "DESCRIBE `task_define`";
		$result = mysql_query( $sql , $conn );
		if( ! $result || mysql_errno( $conn ) > 0 )
		{
			echo "[WARNING]Mysql CHECK task_define table Error, Msessage: " . mysql_error( $conn ) . "\n";
		}
		if( $config['dbClassName'] != 'Dber' )
		{
			echo "[WARNING]Selected Db Engine not using midware db engine.\n";
		}
		if( ! class_exists( 'fm_dbmidware' ) )
		{
			echo "[ERROR]Please Install 'fm_dbmidware' PHP Extension\n";
			return;
		}
		$this->_checkExtension( $config['midWareDb'] , 'fm_dbmidware' , 'Db Midware Engine' );
		$db = new fm_dbmidware( $config['midWareDb']['host'] , $config['midWareDb']['port'] );
		$result = $db->find( '1234' , '1234#user' , '{"level"}' );
		if( empty( $result ) )
		{
			echo "[ERROR]fm_dbmidware find data error.\n";
			return;
		}
	}

	private function _checkFriendServer( $config )
	{
		$this->_checkExtension( $config['friendServer'] , 'fm_friend' , 'Friend' );
	}

	private function _checkTaskServer( $config )
	{
		$this->_checkExtension( $config['taskServer'] , 'fm_task' , 'Task' );
	}
	
	private function _checkAccumulatorServer( $config )
	{
		$this->_checkExtension( $config['statisticsServer'] , 'fm_accumulate' , 'Accumulate' );
	}
	
	private function _checkLootLogicServer( $config )
	{
		$this->_checkExtension( $config['lootLogicServer'] , 'fm_logic' , 'LootLogic' );
	}
	
	private function _checkNotificationServer( $config )
	{
		$this->_checkExtension( $config['notificationServer'] , 'fm_msg' , 'Notification' );
	}
	
	private function _checkPaymentCenter( $config )
	{
		if( !isset( $config['paymentServer'] ) || !isset( $config['paymentServer']['url'] ) || empty( $config['paymentServer']['url'] ) )
		{
			echo "[ERROR]PayCenter Configuration Error.\n";
			return;
		}
		$user = FM_Pay::init( $config['paymentServer']['url'] )->getUser( mt_rand() );
		if( !$user || !isset( $user['creditsBalance'] ) )
		{
			echo "[ERROR]Get PayCenter Data Error.\n";
		}
	} 
	
	private function _checkExtension(  $server , $extenstion , $name )
	{
		if( !class_exists( $extenstion ) )
		{
			echo "[ERROR]Please Install PHP Extension - {$name}.\n";
			return;
		}
		
		switch (  $extenstion  )
		{
			case 'fm_dbmidware':
				$client = new fm_dbmidware( $server['host'] , $server['port']  );
			break;
			case 'fm_friend':
				$client = new fm_friend( $server['host'] , $server['port']  );
			break;
			case 'fm_task':
				$client = new fm_task( $server['host'] , $server['port']  );
			break;
			case 'fm_accumulate':
				$client = new fm_accumulate( $server['host'] , $server['port']  );
			break;
			case 'fm_logic':
				$client = new fm_logic( $server['host'] , $server['port']  );
			break;
			case 'fm_msg':
				$client = new fm_msg( $server['host'] , $server['port']  );
			break;
		}
	}
}
?>