<?php
/**
 *
 */
class RestClient
{
	protected $serverAddr;
	protected $protocolType;
	protected $timeout;
	
	public function __construct( $serverAddr , $protocalType = "web" , $timeout = 3 )
	{
		switch( $protocalType )
		{
			case "tcp":
				$this->serverAddr = $serverAddr;
				$this->protocolType = "tcp";
				break;
				
			default:
				$this->serverAddr = $serverAddr ."?";
				$this->protocolType = "web";
				break;
		}
		$this->timeout = $timeout;
	}
	
	public function callMethod( $method = "", $params )
	{
		$data = $this->post_request( $method = "" , $params );
		$result = json_decode( $data, true );
		
		if( !is_array( $result ) )
		{
			throw new Exception( $data ? $data : "Server Error" , 1000 );
		}
		
		if( !empty( $result['code'] ) )
		{
			throw new Exception( $result['desc'] , $result['code'] );
		}
		
		return $result;
	}
	
	protected function post_request($method, $params)
	{
		$post_string = $this->create_post_string( $method , $params );
		$ch = curl_init();
		curl_setopt( $ch , CURLOPT_POSTFIELDS , $post_string );
		curl_setopt( $ch , CURLOPT_RETURNTRANSFER , true );
		curl_setopt( $ch , CURLOPT_CONNECTTIMEOUT , $this->timeout );
		curl_setopt( $ch , CURLOPT_TIMEOUT , $this->timeout );
		curl_setopt( $ch , CURLOPT_POST, 1);
		
		switch( $this->protocolType )
		{
			case "web":
				$useragent = 'REST API PHP5 Client 1.0 (curl) ' . phpversion();
				curl_setopt($ch, CURLOPT_USERAGENT, $useragent);
				curl_setopt( $ch , CURLOPT_URL , $this->serverAddr );
				
				if( strpos( $this->serverAddr , "https" ) !== false )
					curl_setopt( $ch , CURLOPT_SSL_VERIFYPEER , false );
					
				break;
			case "tcp":
				$address = split( ":" , $this->serverAddr );
				curl_setopt( $ch , CURLOPT_URL , $address[0] );
				curl_setopt( $ch , CURLOPT_PORT , $address[1] );
				break;
		}
		$result = curl_exec($ch);
		if( curl_errno( $ch ) > 0 )
		{
			$result = curl_error( $ch );
		}
		curl_close($ch);
		return $result;
	}
	
	protected function create_post_string($method, $params) 
	{
		switch( $this->protocolType )
		{
			case "web":
				$post_params = array();
				foreach ($params as $key => &$val) {
					$post_params[] = $key.'='.urlencode($val);
				}
				if( empty($method) )
				{
					return implode( '&', $post_params );
				}
				
				return "method={$method}&" . implode('&', $post_params );
			case "tcp":
				$params["cmd"] = $method;
				return json_encode( $params );
		}
	}
}

?>