<?php
class InuMongoDB
{    
    //Mongodb连接  
    private $mongo;
    
    private  $currDBName;
    
    private  $currTableName;  
    
    private  $error;  
    /** 
    * 构造函数 
    * 支持传入多个mongoServer( 1.一个出问题时连接其它的server 2.自动将查询均匀分发到不同server ) 
    * 
    * 参数： 
    * $mongoServer:数组或字符串-array( "127.0.0.1:1111", "127.0.0.1:2222" )-"127.0.0.1:1111" 
    * $connect:初始化mongo对象时是否连接，默认连接 
    * $autoBalance:是否自动做负载均衡，默认是 
    * 
    * 返回值： 
    * 成功：mongo object 
    * 失败：false 
    */  
    protected function __construct( $config )  
    {  
    	$autoBalance = true;
    	$connect = true;
    	
        if(  is_array(  $config  )  )  
        {  
            $mongoServerNum  =  count( $config );  
            if ( $mongoServerNum > 1 && $autoBalance )  
            {  
                $priorServerNum = rand( 1, $mongoServerNum );  
                $randKeys = array_rand( $config,$mongoServerNum );  
                $mongoServerStr = $config[$priorServerNum-1];  
                foreach ( $randKeys as $key )  
                {  
                    if ( $key != $priorServerNum - 1 )  
                    {  
                        $mongoServerStr .=  ',' . $config[$key];  
                    }  
                }  
            }  
            else  
          {  
                	$mongoServerStr  =  implode( ',', $config );  
             }  
        }
       	else  
      	{  
            	$mongoServerStr  =  $config;  
      	}

        $this->mongo = new Mongo(  $mongoServerStr , array( 'connect' => $connect ) );  
    }  
  
    /**
     * 取单例对象
     * @param unknown $config
     * @param unknown $flag
     * @return InuMongoDB
     */
    public static  function getInstance( $config, $flag = array(  ) )  
    {  
        static $mongodbArr;  
        if( empty( $flag['tag'] ) )  
        { 
           $flag['tag']  =  'default';          }  
        	if ( isset( $flag['force'] ) && $flag['force'] == true )  
        	{  
            	$mongo  =  new self( $config );  
            	if( empty( $mongodbArr[$flag['tag']] ) )  
            	{  
                	$mongodbArr[$flag['tag']]  =  $mongo;  
            	} 
            	return $mongo;  
        }
        else if ( isset( $mongodbArr[$flag['tag']] ) && is_resource( $mongodbArr[$flag['tag']] ) )  
        {  
            return $mongodbArr[$flag['tag']];  
        } 
        else  
       {  
            $mongo  =  new self( $config );  
            $mongodbArr[$flag['tag']]  =  $mongo;  
            return $mongo;                  
        }
    } 
  
    /** 
    * 连接mongodb server 
    * 
    * 参数：无 
    * 
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
   public function connect(  )  
    {  
        try {  
            $this->mongo->connect();  
            return true;  
        }  
        catch ( MongoConnectionException $e )  
        {  
            $this->error = $e->getMessage();  
            return false;  
        }  
    }  
  
    /** 
    * select db 
    * 
    * 参数：$dbname 
    * 
    * 返回值：无 
    */  
    public function selectDb( $dbname )  
    {  
        $this->currDBName  =  $dbname;  
    }  
  
    /** 
    * 创建索引：如索引已存在，则返回。 
    * 
    * 参数： 
    * $table_name:表名 
    * $index:索引-array( "id" = >1 )-在id字段建立升序索引 
    * $index_param:其它条件-是否唯一索引等 
    * 
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
   public function ensureIndex( $collection, $index, $indexParam = array(  ) )  
    {  
        $dbname  =  $this->currDBName;  
        $indexParam['safe']  =  1;  
        try {  
            $this->mongo->$dbname->$collection->ensureIndex( $index, $indexParam );  
            return true;  
        }  
        catch ( MongoCursorException $e )  
        {  
            $this->error  =  $e->getMessage(  );  
            return false;  
        }  
    }  
  
    /** 
    * 插入记录 
    * 
	*		$mo = new Mongo();
	*		$coll = $mo->dbname->collname;//获得一个collection对象
    * 
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
    public function insert( $collection, $record )  
    {  
        $dbname  =  $this->currDBName;  
        try {  
           $rs =  $this->mongo->$dbname->$collection->insert( $record, array( 'safe' => true ) );  
            return true;  
        }  
        catch ( MongoCursorException $e )  
        {  
        	
            $this->error  =  $e->getMessage(  );  
            return false;  
        }  
    }  
  
    /** 
    * 查询表的记录数 
    * 
    * 参数： 
    * $table_name:表名 
    * 
    * 返回值：表的记录数 
    */  
    //  return $this->cursor()->count($withLimit);
    public function count( $collection , $queryCondition = array() )  
    {  
        $dbname  =  $this->currDBName;
       // $this->ensureIndex($collection, array('uid' => 1 ));
        //$startTime = microtime( true );
        
        $rs = $this->mongo->$dbname->$collection->count($queryCondition);
        //$endTime = microtime( true );
    
        /*
        echo "<br>";
        echo "useTime = ".sprintf( "%6.fS" , ( $endTime - $startTime )  );
        var_dump( $rs );
*/
        return $rs;
    }
   
    /**
     * 使用map/reduce做聚合查询
     * @param unknown $collection
     * @param unknown $keys
     * @param unknown $initial
     * @param unknown $reduce
     * @param string $condition
     * @return unknown
     */
    public function group( $collection , $keys , $initial , $reduce , $condition  = null )
    {
    	$dbname = $this->currDBName;
    	if( $condition )
    	{
	    	$rs = $this->mongo->$dbname->$collection->group( $keys, $initial, $reduce , $condition );
    	}
    	else
    	{
    		$rs = $this->mongo->$dbname->$collection->group( $keys, $initial, $reduce );
    	}
    	return $rs['retval'];
    }
    
    /**
     * 聚合函数
     * @param unknown $collection
     * @param unknown $query
     * @return unknown
     * 用法见:http://www.php.net/manual/en/mongocollection.aggregate.php
     */
    public function aggregate( $collection , $query )
    {
    	$dbname = $this->currDBName;
    	$rs = $this->mongo->$dbname->$collection->aggregate( $query );
    	return $rs;
    }

    /** 
    * 更新记录 
    * 
    * 参数： 
    * $table_name:表名 
    * $condition:更新条件 
    * $newdata:新的数据记录 
    * $options:更新选择-upsert/multiple 
    * 
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
    public function update( $collection, $condition, $newdata, $options = array(  ) )  
    {  
        $dbname  =  $this->currDBName;  
        $options['safe']  =  1;  
        if ( !isset( $options['multiple'] ) )  
        {  
            $options['multiple']  =  0;          }  
        try {  
           $rs = $this->mongo->$dbname->$collection->update( $condition, $newdata, $options );  
           if( $rs['updatedExisting'] == false )
           {
           		return false;
           }
           return true;
           
        } 
        catch ( MongoCursorException $e )  
        {
            $this->error  =  $e->getMessage(  );  
            return false;  
        }         
    }
  
    /** 
    * 删除记录 
    * 
    * 参数： 
    * $table_name:表名 
    * $condition:删除条件 
    * $options:删除选择-justOne 
    * 
    * 返回值： 
    * 成功：true 
    * 失败：false 
    */  
    public function remove( $collection, $condition, $options = array(  ) )  
    {  
        $dbname  =  $this->currDBName;  
        $options['safe']  =  1;  
        try {  
            $this->mongo->$dbname->$collection->remove( $condition, $options );  
            return true;  
        }  
        catch ( MongoCursorException $e )  
        {  
            $this->error  =  $e->getMessage(  );  
            return false;  
        }
    }  
  
    /** 
    * 查找记录 
    * 
    * 参数： 
    * $table_name:表名 
    * $query_condition:字段查找条件 
    * $result_condition:查询结果限制条件-limit/sort等 
    * $fields:获取字段 
    * 
    * 返回值： 
    * 成功：记录集 
    * 失败：false 
    */  
    public function find( $collection, $queryCondition, $resultCondition = array(  ), $fields = array(  ) )  
    {  
        $dbname  =  $this->currDBName;  
        
        $cursor = $this->mongo->$dbname->$collection->find( $queryCondition, $fields );  
   
        if ( !empty( $resultCondition['start'] ) )  
        {  
            $cursor->skip( $resultCondition['start'] );  
        }  
        if ( !empty( $resultCondition['limit'] ) )  
        {  
            $cursor->limit( $resultCondition['limit'] );  
        }
        if ( !empty( $resultCondition['sort'] ) )  
        {  
            $cursor->sort( $resultCondition['sort'] );  
        }
        
        
        $result = array();
        try {
        	while ($cursor->hasNext())
        	{
        		$result[] = $cursor->getNext();
        	}
        }
        catch (MongoConnectionException $e)
        {
        	$this->error = $e->getMessage();
        	return false;
        }
        catch (MongoCursorTimeoutException $e)
        {
        	$this->error = $e->getMessage();
        	return false;
        }
        return $result;
        
        //return $cursor;  
    }  
  
    /** 
    * 查找一条记录 
    * 
    * 参数： 
    * $table_name:表名 
    * $condition:查找条件 
    * $fields:获取字段 
    * 
    * 返回值： 
    * 成功：一条记录 
    * 失败：false 
    */  
    public function findOne( $collection, $condition, $fields = array(  ) )  
    {  
        $dbname  =  $this->currDBName;  
        return $this->mongo->$dbname->$collection->findOne( $condition, $fields );  
    }  
  
    
    /**
     * 查找不重复的值
     * @param unknown $collection
     * @param unknown $condition
     */
    public function distinct( $collection,  $key , $condition )
    {
    	$dbname  =  $this->currDBName;
	if(  $condition )
	{
  		$ret =  $this->mongo->$dbname->$collection->distinct( $key ,  $condition );
	}
	else
	{
		 $ret =  $this->mongo->$dbname->$collection->distinct( $key  );
	}

	return $ret;
    }
    
    /** 
    * 获取当前错误信息 
    * 
    * 参数：无 
    * 
    * 返回值：当前错误信息 
    */  
   public function getError(  )  
    {  
        return $this->error;  
    }  
}  
?>
