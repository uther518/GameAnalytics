<?php

if( !defined( 'IN_INU' ) )
{
	return;
}

class Helper_Http
{
	/**
	 * post请求
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
	public static function get( $url , $data = array() )
	{
		if( is_array( $data ) && count( $data ) > 0 ) $url .= http_build_query( $data );
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		
		$receiptData = curl_exec($ch);
		curl_close($ch);
		
		return $receiptData;
	}
	
	/**
	 * post请求
	 * @param unknown $url
	 * @param unknown $data
	 * @return mixed
	 */
	public static function post( $url , $data )
	{
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data );
		
		$receiptData = curl_exec($ch);
		curl_close($ch);
		
		return $receiptData;
	}
	
	public static function redirect( $url ){
		header ( "HTTP/1.1 301 Moved Permanently" );
		header ( "Location: " . $url );
		exit();
	}
	
	public static function makeQueryString($params){
		if (is_string($params))
			return $params;
			
		$query_string = array();
	    foreach ($params as $key => $value)
	    {   
	        array_push($query_string, rawurlencode($key) . '=' . rawurlencode($value));
	    }   
	    $query_string = join('&', $query_string);
	    return $query_string;
	}
	
}

?>