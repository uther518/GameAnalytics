<?php


if( !defined( 'IN_INU' ) )
{
	return;
}

class Helper_Date
{
	/**
	 * 英文语言
	 */
	const LANG_EN = 0;
	
	/**
	 * 繁体中文语言
	 */
	const LANG_TC = 1;
	
	/**
	 * 简体中文语言
	 */
	const LANG_SC = 2;
	
	/**
	 * 日文语言
	 */
	const LANG_JP = 3;
	
	/**
	 * 韩文语言
	 */
	const LANG_KR = 4;
	
	/**
	 * 一天的秒数
	 * @var	int
	 */
	const ONE_DAY_SECONDS = 86400;
	
	/**
	 * 格式化时间与时间之间的差距
	 * @param	int $time1	时间1
	 * @param	int $time2	时间2（默认当前时间）
	 * @return	string
	 */
	public static function formatTimeDistance( $time1 , $time2 = null , $isConfuse = true , $lang = Helper_Date::LANG_SC )
	{
		//判断时间2是否为空
		if( $time2 === null )
		{
			//为空：获取当前时间
			$time2 = time();
		}
		//不为空：继续
		
		//计算时间2和时间1之间的秒数差距
		$time = abs( $time2 - $time1 );
		
		//初始化语言词典
		static $langs = array(
			Helper_Date::LANG_EN => array(
				"asSoon" => "As Soon" ,
				"second" => "Second" ,
				"minute" => "Minute" ,
				"hour" => "Hour" ,
				"day" => "Day" ,
				"month" => "Month" ,
				"year" => "Year" ,
				"multiSecond" => "Seconds" ,
				"multiMinute" => "Minutes" ,
				"multiHour" => "Hours" ,
				"multiDay" => "Days" ,
				"multiMonth" => "Months" ,
				"multiYear" => "Years" ,
				"ago" => " ago" ,
				"after" => " after" ,
			) ,
			Helper_Date::LANG_TC => array(
				"asSoon" => "剛剛" ,
				"second" => "秒" ,
				"minute" => "分鐘" ,
				"hour" => "小時" ,
				"day" => "天" ,
				"month" => "個月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分鐘" ,
				"multiHour" => "小時" ,
				"multiDay" => "天" ,
				"multiMonth" => "個月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "後" ,
			) ,
			Helper_Date::LANG_SC => array(
				"asSoon" => "刚刚" ,
				"second" => "秒" ,
				"minute" => "分钟" ,
				"hour" => "小时" ,
				"day" => "天" ,
				"month" => "个月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分钟" ,
				"multiHour" => "小时" ,
				"multiDay" => "天" ,
				"multiMonth" => "个月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "后" ,
			) ,
			Helper_Date::LANG_JP => array(
				"asSoon" => "ついさっき" ,
				"second" => "秒" ,
				"minute" => "分" ,
				"hour" => "時間" ,
				"day" => "日" ,
				"month" => "ヶ月" ,
				"year" => "年" ,
				"multiSecond" => "秒" ,
				"multiMinute" => "分" ,
				"multiHour" => "時間" ,
				"multiDay" => "日" ,
				"multiMonth" => "ヶ月" ,
				"multiYear" => "年" ,
				"ago" => "前" ,
				"after" => "後う" ,
			) ,
			Helper_Date::LANG_KR => array(
				"asSoon" => "방금" ,
				"second" => "초" ,
				"minute" => "분" ,
				"hour" => "시간" ,
				"day" => "일" ,
				"month" => "개 월" ,
				"year" => "년" ,
				"multiSecond" => "초" ,
				"multiMinute" => "분" ,
				"multiHour" => "시간" ,
				"multiDay" => "일" ,
				"multiMonth" => "개 월" ,
				"multiYear" => "년" ,
				"ago" => "앞" ,
				"after" => "뒤" ,
			) ,
		);
		
		//判断差距时间是否少于5秒
		if( $time <= 5 )
		{
			//少于等于5秒：返回刚刚
			return $langs[$lang]["asSoon"];
		}
		//大于5秒：继续
		
		//初始化单位字典
		static $unitDictionary = null;
		if( $unitDictionary == null )
		{
			$unitDictionary = array(
				array(
					"unit" => $langs[$lang]["second"] ,
					"units" => $langs[$lang]["multiSecond"] ,
					"upPoint" => 60 ,
				) ,
				array(
					"unit" => $langs[$lang]["minute"] ,
					"units" => $langs[$lang]["multiMinute"] ,
					"upPoint" => 60 ,
				) ,
				array(
					"unit" => $langs[$lang]["hour"] ,
					"units" => $langs[$lang]["multiHour"] ,
					"upPoint" => 24 ,
				) ,
				array(
					"unit" => $langs[$lang]["day"] ,
					"units" => $langs[$lang]["multiDay"] ,
					"upPoint" => 30 ,
				) ,
				array(
					"unit" => $langs[$lang]["month"] ,
					"units" => $langs[$lang]["multiMonth"] ,
					"upPoint" => 12 ,
				) ,
				array(
					"unit" => $langs[$lang]["year"] ,
					"units" => $langs[$lang]["multiYear"] ,
					"upPoint" => 1000 ,
				) ,
			);
		}
		
		//循环计算计算
		$returnValue = "";
		$unitPos = 0;
		while( $time > 0 && $unitPos < 6 )
		{
			$howUnit = $time % $unitDictionary[$unitPos]["upPoint"];
			
			//如果当前数量大于0
			if( $howUnit > 0 )
			{
				//加入返回值
				if( $isConfuse )
				{
					$returnValue = $howUnit . $unitDictionary[$unitPos]["unit"];
				}
				else 
				{
					$returnValue = $howUnit . $unitDictionary[$unitPos]["unit"] . $returnValue;
				}
			}
			
			$time = intval( $time / $unitDictionary[$unitPos]["upPoint"] );
			$unitPos++;
		}
		
		$returnValue .= ( $time2 > $time1 ? $langs[$lang]["ago"] : $langs[$lang]["after"] );
		
		return $returnValue;
	}
	
	/**
	 * 计算给予时间的当天起始时间和结束时间
	 * @param	int $time	UNIX时间戳
	 * @return	array(
	 * 				startTime:int	//起始时间
	 * 				endTime:int		//结束时间
	 * 			)
	 */
	public static function computeTodayTime( $time )
	{
		//获取当前时区与格林威治时间相差多少秒
		$secondOfTimeZone = date( "Z" );
		
		//先把指定时间加上了时区相差的描述
		$time += $secondOfTimeZone;
		
		//计算起始时间和结束时间，并返回
		return array(
			"startTime" => ( $time - ( $time % self::ONE_DAY_SECONDS ) - $secondOfTimeZone ) ,
			"endTime" => ( $time - ( $time % self::ONE_DAY_SECONDS ) - $secondOfTimeZone ) + self::ONE_DAY_SECONDS - 1 ,
		);
	}
	
	/**
	 * 计算给予时间的昨天起始时间和结束时间
	 * @param	int $time	UNIX时间戳
	 * @return	array(
	 * 				startTime:int	//起始时间
	 * 				endTime:int		//结束时间
	 * 			)
	 */
	public static function computeYestodayTime( $time )
	{
		$todayTime = self::computeTodayTime( $time );
		return array(
			"startTime" => $todayTime['startTime'] - self::ONE_DAY_SECONDS ,
			"endTime" => $todayTime['endTime'] - self::ONE_DAY_SECONDS ,
		);
	}
	
	/**
	 * 获得给定时间所在周开始时间戳(周一0点)
	 * */
	public static function getWeekStartTime( $time = null )
	{
		if( !$time ) $time = $_SERVER['REQUEST_TIME'];
		return strtotime( date( 'Y-m-d' , $time - ( date( 'N' , $time ) - 1 ) * self::ONE_DAY_SECONDS ) );
	}
	
	/**
	 * 获得给定时间所在周结束时间戳(周日24点)
	 * */
	public static function getWeekEndTime( $time = null )
	{
		if( !$time ) $time = $_SERVER['REQUEST_TIME'];
		return strtotime( date( 'Y-m-d' , $time - ( date( 'N' , $time ) - 8 ) * self::ONE_DAY_SECONDS ) );
	}
	
	/**
	 * 获得当天剩余秒数
	 * @return int
	 */
	public static function getTodayLeftSeconds()
	{
		return strtotime( date( 'Y-m-d' ) ) + self::ONE_DAY_SECONDS - $_SERVER['REQUEST_TIME'];
	}
}

?>