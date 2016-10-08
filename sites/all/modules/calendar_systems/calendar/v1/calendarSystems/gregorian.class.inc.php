<?php
/**
* @author Sina Salek
* @changes
* 	
* @todo
* 
*/

/**
* @desc 
*/
class cmfcCalendarV1Gregorian extends cmfcCalendarV1 {


    var $_monthsName=array(
    	'1'=>'January',
    	'2'=>'February',
    	'3'=>'March',
    	'4'=>'April',
    	'5'=>'May',
    	'6'=>'June',
    	'7'=>'July',
    	'8'=>'August',
    	'9'=>'September',
    	'10'=>'October',
    	'11'=>'November',
    	'12'=>'December'
    );
    
    var $_monthsShortName=array(
    	'1'=>'&#1601;&#1585;&#1608;',
    	'2'=>'&#1575;&#1585;&#1583;',
    	'3'=>'&#1582;&#1585;&#1583;',
    	'4'=>'&#1578;&#1610;&#1585;',
    	'5'=>'&#1605;&#1585;&#1583;',
    	'6'=>'&#1588;&#1607;&#1585;',
    	'7'=>'&#1605;&#1607;&#1585;',
    	'8'=>'&#1570;&#1576;&#1575;',
    	'9'=>'&#1570;&#1584;&#1585;',
    	'10'=>'&#1583;&#1609;',
    	'11'=>'&#1576;&#1607;&#1605;',
    	'12'=>'&#1575;&#1587;&#1601;'
    );
    
    var $_weeksName=array(
    	'0'=>'&#1610;&#1603;&#1588;&#1606;&#1576;&#1607;',//Sunday
    	'1'=>'&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;',
    	'2'=>'&#1587;&#1607;&#32;&#1588;&#1606;&#1576;&#1607;',
    	'3'=>'&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;',
    	'4'=>'&#1662;&#1606;&#1580;&#1588;&#1606;&#1576;&#1607;',
    	'5'=>'&#1580;&#1605;&#1593;&#1607;',
    	'6'=>'&#1588;&#1606;&#1576;&#1607;'//Saturday
    );
    
    var $_weeksShortName=array(
    	'0'=>'&#1610;',//Sunday
    	'1'=>'&#1583;',
    	'2'=>'&#1587;',
    	'3'=>'&#1670;',
    	'4'=>'&#1662;',
    	'5'=>'&#1580;',
    	'6'=>'&#1588;'//Saturday
    );
        
    var $_meridiemsName=array(
    	'am'=>'&#1602;&#1576;&#1604;&#8207;&#1575;&#1586;&#1592;&#1607;&#1585;',
    	'pm'=>'&#1576;&#1593;&#1583;&#1575;&#1586;&#1592;&#1607;&#1585;',
    );
    
    var $_meridiemsShortName=array(
    	'am'=>'&#1602;&#46;&#1592;',
    	'pm'=>'&#1576;&#46;&#1592;',
    );
    
    var $_weekDaysHoliday=array(0,1);
    
    
    function timestampToStr($format,$timestamp=null) {
    	if (is_null($timestamp)) {
    		$timestamp=$this->phpTime();;
		}
    	return $this->phpDate($format,$timestamp);
	}
    
    function strToTimestamp($string) {
    	return strtotime($string);
	}
	
	function timestampToInfoArray($timestamp=null) {
		if (is_null($timestamp)) $timestamp=$this->phpTime();;
		$arr=$this->phpGetDate($timestamp);
		
		$arr['month']=$arr['mon'];
		$arr['day']=$arr['mday'];

		$arr['monthName']=$this->getMonthName($arr['month']);
		$arr['monthShortName']=$this->getMonthShortName($arr['month']);
		
		$arr['monthFirstDayWeekday']=$this->phpDate('w',$this->infoArrayToTimestamp(array('year'=>$arr['year'],'month'=>$arr['month'],'day'=>'1')))+1;
		$arr['monthDaysNumber']=$this->phpDate('t',$timestamp);
		
		$arr['weekday']++;
		$arr['weekday']=$arr['wday'];
		$arr['weekdayName']=$this->getWeekName($arr['weekday']);
		$arr['weekdayShortName']=$this->getWeekShortName($arr['weekday']);
		
		return $arr;
	}
	
	function infoArrayToTimestamp($arr) {
		return mktime(0,0,0,$arr['month'],$arr['day'],$arr['year']);
	}
    

    function dateDiff($first,$second) {
        $first_date = explode("-",$first);
        $first_date = mktime(0, 0, 0, $first_date[1],$first_date[2], $first_date[0]);
        //echo $first_date[1];
        $second_date = explode("-",$second);
        $second_date = mktime(0, 0, 0,$second_date[1],$second_date[2], $second_date[0]);
        $totalsec=$second_date- $first_date;
        return $totalday = round(($totalsec/86400));
    }
    
    
    
    /**
    * translate number of month to name of month
    */
    function getWeekName($weekNumber)
    {
		return html_entity_decode($this->_weeksName[$weekNumber]);
    }
    
    function getWeekShortName($weekNumber)
    {
		return html_entity_decode($this->_weeksShortName[$weekNumber]);
    }
        
    /**
    * translate number of month to name of month
    */
    function getMonthName($month)
    {
		return html_entity_decode($this->_monthsName[$month]);
    }
    
    function getMonthShortName($month)
    {
        return html_entity_decode($this->_monthsShortName[$month]);
    }
    
    function getMeridiemName($m)
    {
        return html_entity_decode($this->_meridiemsName[$m]);
    }
    
    function getMeridiemShortName($m)
    {
        return html_entity_decode($this->_meridiemsShortName[$m]);
    }
    
    
    function dateTimeDiff($first_timestamp,$second_timestamp)
    {
        // Author: Tone.
        // Date    : 15-12-2003.
        
        // Ref: Dates go in "2003-12-31".
        // Ref: Times go in "12:59:13".
        // Ref: mktime(HOUR,MIN,SEC,MONTH,DAY,YEAR).
        
        // Splits the dates into parts, to be reformatted for mktime.
    //    $first_datetime = getdate($first_datetime);
    //    $second_datetime = getdate($second_datetime);
        
        // makes the dates and times into unix timestamps.
        // $first_unix  = mktime($first_datetime['hours'], $first_time_ex[1], $first_time_ex[2], $first_date_ex[1], $first_date_ex[2], $first_date_ex[0]);
        // $second_unix  = mktime($second_time_ex[0], $second_time_ex[1], $second_time_ex[2], $second_date_ex[1], $second_date_ex[2], $second_date_ex[0]);
        // Gets the difference between the two unix timestamps.
        if (empty($first_timestamp) or $first_timestamp<0 or empty($second_timestamp) or $second_timestamp<0)
            return false;
        $timediff = $first_timestamp-$second_timestamp;
                   
        // Works out the days, hours, mins and secs.
        $days=intval($timediff/86400);
        $remain=$timediff%86400;
        $hours=intval($remain/3600);
        $remain=$remain%3600;
        $mins=intval($remain/60);
        $secs=$remain%60;
        // Returns a pre-formatted string. Can be chagned to an array.
        $result['days']=$days;
        $result['hours']=$hours;
        $result['minutes']=$mins;
        return $result;
    }
    
    

    function jGetDate($timestamp="",$transNumber=1)
    {
        if($timestamp=="")
            $timestamp=$this->phpTime();

        return array(
            0=>$timestamp,    
            "seconds"=>jdate("s",$timestamp,$transNumber),
            "minutes"=>jdate("i",$timestamp,$transNumber),
            "hours"=>jdate("G",$timestamp,$transNumber),
            "mday"=>jdate("j",$timestamp,$transNumber),
            "wday"=>jdate("w",$timestamp,$transNumber),
            "mon"=>jdate("n",$timestamp,$transNumber),
            "year"=>jdate("Y",$timestamp,$transNumber),
            "yday"=>cmfcDateTime::yearTotalDays(jdate("m",$timestamp,$transNumber),jdate("d",$timestamp,$transNumber),jdate("Y",$timestamp,$transNumber)),
            "weekday"=>jdate("l",$timestamp,$transNumber),        
            "month"=>jdate("F",$timestamp,$transNumber),
        );
    }



    function change_to_miladi($date)
    {
      $date = explode("-",$date);
      $date = cmfcDateTime::jalaliToGregorian($date[0],$date[1],$date[2]);
      //$date[1] = $date[1] -1;
      $date = $date[0]."-".$date[1]."-15";
      return $date;
    }


    function date_fa($date) {
       list($year, $month, $day) = preg_split ( '/-/', $date);
       list($jyear, $jmonth, $jday) = cmfcDateTime::gregorianToJalali($year, $month, $day);
       $date = jmaketime(0,0,0,$jmonth,$jday,$jyear) ;
       $date = jdate("d M Y",$date) ;
       return $date;
    }

    function date_en($date) {
      $date = explode("-",$date);
      $date = date("F j, Y",mktime(0, 0, 0, $date[1], $date[2],$date[0]));
      return $date;
    }


    function dateByLanguage($format,$time_stamp,$lang) {
        if ($lang=='fa') {
            return cmfcJalaliDateTime::smartGet($format,$time_stamp,1);
        } else {
            return date($format,cmfcJalaliDateTime::toTimeStamp($time_stamp));
        }
    }



}