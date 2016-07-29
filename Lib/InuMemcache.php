<?php
/**
 * 使用Memcache类
 *
 */
class InuMemcache
{
    protected $flag = MEMCACHE_COMPRESSED;	//默认Flag
    protected $expire = 0;	//默认有效期(无限期)
    
    protected $hasOld = false;	//是否有老集群
    
    protected $config;	//当前集群配置
    protected $link = array();	//当前集群连接池
    protected $serverSum = 1;	//当前集群数量
    protected $serverNow = -1;	//当前集群正使用的连接
    
    protected $oldConfig;	//老集群配置
    protected $oldLink = array();	//老集群连接池
    protected $oldServerSum = 1;	//老集群数量
    protected $oldServerNow = -1;	//老集群正使用的连接
    
    /**
     * 初始化配置
     *
     * @param array $config	//集群配置
     */
    public function __construct( $config )
    {
    
        if( isset( $config['current'] ) && isset( $config['old'] ) )
        {
        	$this->config = $config['current'];
    		$this->serverSum = count( $config['current'] );
    		
        	$this->oldConfig = $config['old'];
        	$this->oldServerSum = count( $config['old'] );
        	
        	$this->hasOld = true;
        }
        else
        {
        	$this->config = $config;
        	$this->serverSum = count( $config );
        }
    }

    /**
     * 切换当前集群连接
     *
     * @param string $key
     * @param int $serverId
     */
    protected function selectServer( $key = '' , $serverId = false )
    {
        $serverId = ( $serverId === false ) ? intval( $key ) % $this->serverSum : $serverId % $this->serverSum;
        if( ( $this->serverNow != $serverId ) && ( !isset( $this->link[ $serverId ] ) ) )
        {
        	$this->link[ $serverId ] = new Memcache();
            $rs = $this->link[ $serverId ]->addServer( $this->config[ $serverId ][ 'host' ] , $this->config[ $serverId ]['port'] );
        }
        
        $this->serverNow = $serverId;
    }
    
    /**
     * 切换老集群连接
     *
     * @param string $key
     * @param int $serverId
     */
	protected function selectOldServer( $key = '' , $serverId = false )
	{
		$serverId = ( $serverId === false ) ? intval( $key ) % $this->oldServerSum : $serverId % $this->oldServerSum;
		if( ( $this->oldServerNow != $serverId ) && ( !isset( $this->oldLink[ $serverId ] ) ) )
		{
			$this->oldLink[ $serverId ] = new Memcache();
		    $this->oldLink[ $serverId ]->addServer( $this->oldConfig[ $serverId ][ 'host' ] , $this->oldConfig[ $serverId ]['port'] );
		}
		
		$this->oldServerNow = $serverId;
	}
    
	/**
	 * 设置到缓存
	 *
	 * @param string $key
	 * @param mixed
	 * @param int $expire
	 * @param int $flag
	 * @param int $serverId
	 * @return boolean
	 */
    public function set( $key , $value , $expire = 0 , $flag = 0 , $serverId = false )
    {
        $flag = ( $flag > 0 ) ? $flag : $this->flag;
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        $this->selectServer( $key , $serverId );
		return $this->link[ $this->serverNow ]->set( $key , $value , $flag , $expire );
    }
    
    /**
     * 添加到缓存(存在则返回添加失败)
     *
     * @param string $key
     * @param mixed
     * @param int $expire
     * @param int $flag
     * @return boolean
     */
    public function add( $key , $value , $expire = 20 , $flag = 0 )
    {
        $this->selectServer( $key );
        $expire = ( $expire > 0 ) ? $expire : $this->expire;
        $flag = ( $flag > 0 ) ? $flag : $this->flag; 
        return $this->link[ $this->serverNow ]->add( $key , $value , $flag , $expire );
    }

    /**
     * 获取缓存数据
     * 		支持单键获取时平滑扩容
     *  	支持多键获取
     * 		
     *  
     *
     * @param string $key
     * @param int $serverId
     * @return mixed
     */
    public function get( $key , $serverId = false )
    {
    	//获取没有指定服务器ID的多键
    	if( is_array( $key ) && $serverId == false )
    	{
    		$groups = array();
			foreach ( $key as $item )
			{
				$serverId = intval( $item ) % $this->serverSum;
				$groups[ $serverId ][] = $item;
			}
			
			$data = array();
			foreach ( $groups as $serverId => $item )
			{
				$this->selectServer( '' , $serverId );
				$temp = $this->link[ $this->serverNow ]->get( $item );
				if ( !empty( $temp ) )
				{
					$data = $data + $temp;
				}
			}
			
			//若开启平滑扩容,则获取老集群数据
			if( $this->hasOld )
			{
				$remainKeys = Helper_Array::array_diff_fast( $key , array_keys( $data ) );
				if( $remainKeys )
				{
					$groups = array();
					foreach ( $remainKeys as $item )
					{
						$serverId = intval( $item ) % $this->oldServerSum;
						$groups[ $serverId ][] = $item;
					}
					
					foreach ( $groups as $serverId => $item )
					{
						$this->selectOldServer( '' , $serverId );
						$temp = $this->oldLink[ $this->oldServerNow ]->get( $item );
						if ( !empty( $temp ) )
						{
							$data = $data + $temp;
						}
					}
				}
			}
			
			return $data;
    	}
    	
        $this->selectServer( $key , $serverId );
        if( is_array( $key ) )
        {
        	return $this->link[ $this->serverNow ]->get( $key );
        }
        else
        {
        	$info = $this->link[ $this->serverNow ]->get( $key );
        	
        	//平滑扩容迁移操作
        	if( $info === false && $this->hasOld )
        	{
        		$this->selectOldServer( $key );
        		$info = $this->oldLink[ $this->oldServerNow ]->get( $key );
        		if( $info !== false )
        		{
        			$this->add( $key , $info , 0 );
    				$this->oldLink[ $this->oldServerNow ]->delete( $key );
        		}
        	}
        	
        	return $info;
        }
    }

    /**
     * 递增缓存
     *
     * @param string $key
     * @param int $value
     * @return boolean
     */
    public function increment( $key , $value )
    {
        $this->selectServer( $key );
        return $this->link[ $this->serverNow ]->increment( $key , $value );
    }
    
    /**
     * 删除缓存
     *
     * @param string $key
     * @param int $timeOut
     * @param int $serverId
     * @return boolean
     */
    public function delete( $key , $timeOut = 0 , $serverId = false )
    {
        $this->selectServer( $key , $serverId );
        return $this->link[ $this->serverNow ]->delete( $key );
    }
    
    /**
     * 获取错误状态
     * @return	int
     */
    public function getErrorCode()
    {
    	return $this->link[ $this->serverNow ]->getResultCode();
    }
    
    /**
     * 获取错误信息
     * @return	string
     */
    public function getErrorMessage()
    {
    	return $this->link[ $this->serverNow ]->getResultMessage();
    }
    
//    public function getUserIds()
//    {
//    	$userIds = array();
//    	$this->selectServer();
//		$allSlabs = $this->link[ $this->serverNow ]->getExtendedStats( 'slabs' );
//		foreach( $allSlabs as $slabs )
//		{
//			foreach( $slabs as $slabId => $slabMeta )
//			{
//				$slabId = intval( $slabId );
//				$cdump = $this->link[ $this->serverNow ]->getExtendedStats( 'cachedump' , $slabId , 9999999 );
//				foreach( $cdump as $entries )
//				{
//					if( $entries )
//					{
//						foreach( $entries as $eName => $eData )
//						{
//							if( preg_match( "/(\d+)_/", $eName , $matches ))
//							{
//								if( $matches[1] && !in_array( $matches[1], $userIds) )
//								{
//									$userIds[] = $matches[1];
//								}
//							}
//						}
//					}
//				}
//			}
//		}
//		return $userIds;
//    }
}