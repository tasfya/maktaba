<?php
/**
* @author Sina Salek
* @version $Id: iranian.class.inc.php 209 2009-01-20 13:49:37Z Salek $
* 
* Original persian to gregorian convertor by Milad Rastian , Roozbeh Pournader , Mohammad Toossi 
*/
 
 
if (!is_callable('div')) {
    function div($a,$b) {
        return (int) ($a / $b);
    }
}
 
/**
* 
*/
class cmfcCalendarV1Arabic extends cmfcCalendarV1 {
	
	/**
	 * define(ISLAMIC_EPOCH, 1948439.5); 
	 */
	var $_islamicEpoch='1948439.5';
	
	var $_dateFormats=array(
		'rfc10'=>'Y-m-d H:i:s',
		'ArraySimple'=>'���� 5�� �������� 1387'
	);
	
	var $_defaultFormat='rfc10';
	
    var $_monthsName=array(
    	'1'=>'محرم',
    	'2'=>'صفر',
    	'3'=>'ربيع الأول',
    	'4'=>'ربيع الثاني',
    	'5'=>'جمادى الأول',
    	'6'=>'جمادى الآخر',
    	'7'=>'رجب',
    	'8'=>'شعبان',
    	'9'=>'رمضان',
    	'10'=>'شوال',
    	'11'=>'ذو القعدة',
    	'12'=>'ذو الحجة'
    );
    
    var $_monthsShortName=array(
    	'1'=>'محرم',
    	'2'=>'صفر',
    	'3'=>'ربيع الأول',
    	'4'=>'ربيع الثاني',
    	'5'=>'جمادى الأول',
    	'6'=>'جمادى الآخر',
    	'7'=>'رجب',
    	'8'=>'شعبان',
    	'9'=>'رمضان',
    	'10'=>'شوال',
    	'11'=>'ذو القعدة',
    	'12'=>'ذو الحجة'
    );
    
    var $_weeksName=array(
    	'6'=>'السبت',//Saturday
    	'0'=>'الأحد',//Sunday
    	'1'=>'الاثنين',
    	'2'=>'الثلاثاء',
    	'3'=>'الأربعاء',
    	'4'=>'الخميس',
    	'5'=>'الجمعة',
    );
    
    var $_weeksShortName=array(
    	'6'=>'السبت',//Saturday
    	'0'=>'الأحد',//Sunday
    	'1'=>'الاثنين',
    	'2'=>'الثلاثاء',
    	'3'=>'الأربعاء',
    	'4'=>'الخميس',
    	'5'=>'الجمعة',
    );
        
    var $_meridiemsName=array(
    	'am'=>'صباحاً',
    	'pm'=>'مساءاً',
    );
    
    var $_meridiemsShortName=array(
    	'am'=>'صباحاً',
    	'pm'=>'مساءاً',
    );
    
    var $_weekDaysHoliday=array(6);
    

    /**
     * Islamic Calendar
     * @see v1/cmfcCalendarV1#timestampToStr($format, $timestamp)
     */
    function timestampToStr($format,$timestamp=null) {
    	return $this->date($format,$timestamp);
	}
    
	/**
	 * Islamic Calendar 
	 * @see v1/cmfcCalendarV1#strToTimestamp($string)
	 */
    function strToTimestamp($string) {
    	return $this->strtotime($string);
	}
	
	
	function timestampToInfoArray($timestamp=null) {
		$arr=$this->phpGetDate($timestamp);
		if (is_null($timestamp)) $timestamp=$this->phpTime();

		list($arr['year'],$arr['month'],$arr['day'])=$this->fromGregorian($arr['year'],$arr['mon'],$arr['mday']);

		$arr['monthName']=$this->getMonthName($arr['month']);
		$arr['monthShortName']=$this->getMonthShortName($arr['month']);
		
		$arr['monthFirstDayWeekday']=$this->phpDate('w',$this->infoArrayToTimestamp(array('year'=>$arr['year'],'month'=>$arr['month'],'day'=>'1')))+1;
		if ($arr['monthFirstDayWeekday']>=6) {
			$arr['monthFirstDayWeekday']=0;
		}
		$arr['monthDaysNumber']=$this->date('t',$timestamp);
		
		$arr['weekday']++;
		$arr['weekday']=$arr['wday'];
		$arr['weekdayName']=$this->getWeekName($arr['weekday']);
		$arr['weekdayShortName']=$this->getWeekShortName($arr['weekday']);
		
		return $arr;
	}
	
	/**
	 * Islamic Calendar
	 * @see v1/cmfcCalendarV1#infoArrayToTimestamp()
	 */
	function infoArrayToTimestamp($arr) {
		list($gy,$gm,$gd)=$this->toGregorian($arr['year'],$arr['month'],$arr['day']);

		if (!isset($arr['hour'])) {
			$arr['hour']=$this->phpDate('H');
		}
		if (!isset($arr['minute'])) {
			$arr['minute']=$this->phpDate('i');
		}
		if (!isset($arr['second'])) {
			$arr['second']=$this->phpDate('s');
		}
		
		return strtotime("$gy-$gm-$gd".' '.$arr['hour'].':'.$arr['minute'].':'.$arr['second']);
	}
	
	
    
    /**
    * Implementation of PHP date function
    * This is the simplified version by Sina Salek
    */
    function date($format,$maket=null)
    {
        if (is_null($maket) or $maket=='') {
    		$maket=$this->phpTime();
		}
		
        $farsi=1;
        $type=$format;
        //set 1 if you want translate number to farsi or if you don't like set 0
        $transnumber=false;
        ///chosse your timezone
        $TZhours=0;
        $TZminute=0;
        $need="";
        $result1="";
        $result="";

        if (is_null($maket)) {
            $year=$this->phpDate("Y");
            $month=$this->phpDate("m");
            $day=$this->phpDate("d");
            $maket=mktime($this->phpDate("H")+$TZhours,$this->phpDate("i")+$TZminute,$this->phpDate("s"),$this->phpDate("m"),$this->phpDate("d"),$this->phpDate("Y"));
        } else {
            $maket+=$TZhours*3600+$TZminute*60;
            $year=$this->phpDate("Y",$maket);
            $month=$this->phpDate("m",$maket);
            $day=$this->phpDate("d",$maket);
        }

		$need=$maket;
        $i=0;
        $subtype="";
        $subtypetemp="";
        list( $jyear, $jmonth, $jday ) = $this->fromGregorian($year, $month, $day);

        while($i<strlen($type))
        {
            $subtype=substr($type,$i,1);
            if($subtypetemp=="\\")
            {
                $result.=$subtype;
                $i++;
                continue;
            }

            switch ($subtype)
            {
                case "A":
                    $result1=$this->phpDate("a",$need);
                    $result.=$this->getMeridiemName($result1);
                    break;

                case "a":
                    $result1=$this->phpDate("a",$need);
                    $result.=$this->getMeridiemShortName($result1);
                case "d":
                    if($jday<10) $result1="0".$jday;
                        else $result1=$jday;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                        else $result.=$result1;
                    break;
                case "D":
                    $result1=$this->phpDate("w",$need);
                    $result.=$this->getWeekShortName($result1);
                    break;
                case"F":
                    $result.=$this->getMonthName($jmonth);
                    break;
                case "g":
                    $result1=$this->phpDate("g",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                        else $result.=$result1;
                    break;
                case "G":
                    $result1=$this->phpDate("G",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                    case "h":
                    $result1=$this->phpDate("h",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "H":
                    $result1=$this->phpDate("H",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "i":
                    $result1=$this->phpDate("i",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "j":
                    $result1=$jday;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "l":
                    $result1=$this->phpDate("w",$need);
                    $result.=$this->getWeekName($result1);
                    break;
                case "m":
                    if($jmonth<10) $result1="0".$jmonth;
                    else    $result1=$jmonth;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "M":
                    $result.=$this->getMonthShortName($jmonth);
                    break;
                case "n":
                    $result1=$jmonth;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "s":
                    $result1=$this->phpDate("s",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "S":
                    $result.=html_entity_decode("&#1575;&#1605;");
                    break;
                case "t":
                    $result.=$this->monthTotalDays ($month,$day,$year);
                    break;
                case "w":
                    $result1=$this->phpDate("w",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "y":
                    $result1=substr($jyear,2,4);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;
                case "Y":
                    $result1=$jyear;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToNativeNumbers($result1);
                    else $result.=$result1;
                    break;        
                case "U" :
                    $result.=$this->phpTime();
                    break;
                case "Z" :
                    $result.=$this->yearTotalDays($jmonth,$jday,$jyear);
                    break;
                case "L" :
                    list( $tmp_year, $tmp_month, $tmp_day ) = $this->toGregorian(1384, 12, 1);
                    /*
                    echo $tmp_day;
                    if(lastday($tmp_month,$tmp_day,$tmp_year)=="31")
                        $result.="1";
                    else 
                        $result.="0";
                    */
                    break;
                default:
                    $result.=$subtype;
            }
            $subtypetemp=substr($type,$i,1);
       		 $i++;
        }
        return $result;
    }
	
	
    /**
    * accept array,timestamp and string as input datetime in jalali 
    * or gregorian format and convert it to timestamp
    * Implementation of PHP strtotime function
    */
    function strtotime($value) {
    	foreach ($this->_dateFormats as $formatKey=>$formatSample) {
    		$result=call_user_func(array(&$this,'dateFormat'.$formatKey),$value);
    		if ($result!==false) {
    			return $result;
			}
		}
    
        return $value;
    }
    
 
	function dateFormatArraySimple($value) {
        if (is_array($value)) {
            if (isset($value[0])) {
                $y=$value['0'];
                $m=$value['1'];
                $d=$value['2'];
                $h=$value['3'];
                $i=$value['4'];
                $s=$value['5'];
            } elseif (isset($value['year'])) {
                $y=$value['year'];
                $m=$value['month'];
                $d=$value['day'];
                $h=$value['hour'];
                $i=$value['minute'];
                $s=$value['second'];
            } elseif (isset($value['y'])) {
                $y=$value['y'];
                $m=$value['m'];
                $d=$value['d'];
                $h=$value['h'];
                $i=$value['i'];
                $s=$value['s'];
            }
            
            return $this->valueToTimeStamp($y,$m,$d,$h,$i,$s);
        }
        return false;
	}
    
    function dateFormatRfc10($value) {
        if (is_string($value)) {
            if (preg_match('/^([0-9]{2,4})[-\/\\\]([0-9]{1,2})[-\/\\\]([0-9]{1,2})( +([0-9]{1,2})[:]([0-9]{1,2})[:]([0-9]{1,2}))?/', $value, $regs)) {
                $y=$regs['1'];
                $m=$regs['2'];
                $d=$regs['3'];
                $h=$regs['5'];
                $i=$regs['6'];
                $s=$regs['7'];
            }
            return $this->valueToTimeStamp($y,$m,$d,$h,$i,$s);
        }
        
        return false;
	}
	
	
	
	/**
	 * Islamic Calendar
	 * @return Array          Hegri date [int Year, int Month, int Day] (Islamic calendar)
	 * @param  Integer Year   Gregorian year
	 *          Integer Month  Gregorian month
	 *         Integer Day    Gregorian day
	 * @desc   hj_convert will convert given Gregorian date into Hegri date (Islamic calendar)
	 * @author Khaled Al-Shamaa
	 */
	function hj_convert($Y, $M, $D){
		$jd = GregorianToJD($M, $D, $Y);

		list($year, $month, $day) = $this->jd_to_islamic($jd);

		return array($year, $month, $day);
	}

	/**
	 * Islamic Calendar 
	 * @return Array        Hegri date [int Year, int Month, int Day] (Islamic calendar)
	 * @param  Integer jd   Julian day
	 * @desc   jd_to_islamic will convert given Julian day into Hegri date (Islamic calendar)
	 * @author Khaled Al-Shamaa
	*/
	function jd_to_islamic($jd){
		$jd = floor($jd) + 0.5;
		$year = floor(((30 * ($jd - $this->_islamicEpoch)) + 10646) / 10631);
		$month = min(12,ceil(($jd - (29 + $this->islamic_to_jd($year, 1, 1))) / 29.5) + 1);
		$day = ($jd - $this->islamic_to_jd($year, $month, 1)) + 1;

		return array($year, $month, $day);
	}

	/**
	 * Islamic Calendar 
	 * @return Integer        Julian day
	 * @param  Integer Year   Hegri year
	 *         Integer Month  Hegri month
	 *         Integer Day    Hegri day
	 * @desc   islamic_to_jd will convert given Hegri date (Islamic calendar) into Julian day
	 * @author Khaled Al-Shamaa
	 */
	function islamic_to_jd($year, $month, $day){
		return ($day +
			ceil(29.5 * ($month - 1)) +
			($year - 1) * 354 +
			floor((3 + (11 * $year)) / 30) +
			$this->_islamicEpoch) - 1;
	}
            

	/**
	 * Islamic Calendar
	 * @param $g_y
	 * @param $g_m
	 * @param $g_d
	 * @return unknown_type
	 */
    function fromGregorian ($g_y, $g_m, $g_d) 
    {
		$y = $g_y;   
		$m = $g_m;
		$d = $g_d;
		if (( $y > 1582 ) || (( $y == 1582 ) && ( $m > 10 )) || (( $y == 1582 ) && ( $m == 10 ) && ( $d > 14 ))) 
		{
			$jd = (int)(( 1461 * ( $y + 4800 + (int)(( $m - 14 ) / 12 )))/ 4) + (int)(( 367 * ( $m - 2 - 12 * ((int)(( $m - 14 ) / 12)))) / 12) - (int)(( 3 * ((int)(( $y + 4900+ (int)(( $m - 14) / 12) ) / 100))) / 4)+ $d - 32075;
		} else {
			$jd = 367 * $y - (int)(( 7 * ( $y + 5001 + (int)(( $m - 9 ) / 7))) / 4) + (int)(( 275 * $m) / 9) + $d + 1729777;
		}
		$julianday = $jd;
		$l = $jd - 1948440 + 10632;
		$n = (int)(( $l - 1 ) / 10631);
		$l = $l - 10631 * $n + 354;
		$j = ( (int)(( 10985 - $l ) / 5316)) * ( (int)(( 50 * $l) / 17719)) + ( (int)( $l / 5670 )) * ( (int)(( 43 * $l ) / 15238 ));
		$l = $l - ( (int)(( 30 - $j ) / 15 )) * ( (int)(( 17719 * $j ) / 50)) - ( (int)( $j / 16 )) * ( (int)(( 15238 * $j ) / 43 )) + 29;
		$m = (int)(( 24 * $l ) / 709 );
		$d = $l - (int)(( 709 * $m ) / 24);
		$y = 30 * $n + $j - 30;
		

		
		return array($y, $m, $d);
    } 

    /**
     * Islamic Calendar 
     * @param $i_y
     * @param $i_m
     * @param $i_d
     * @return unknown_type
     */
    function toGregorian($i_y, $i_m, $i_d) 
    {
    	//$jd=$this->islamic_to_jd($i_y, $i_m, $i_d);
    	//list($gy, $gm, $gd)=JDToGregorian($jd);
        $y = $i_y;  
        $m = $i_m;
        $d = $i_d;
       
        $jd = (int)((11*$y+3)/30)+354*$y+30*$m-(int)(($m-1)/2)+$d+1948440-385;
        //$this->julianday = $jd;
        if ($jd> 2299160 )
        {
            $l=$jd+68569;
            $n=(int)((4*$l)/146097);
            $l=$l-(int)((146097*$n+3)/4);
            $i=(int)((4000*($l+1))/1461001);
            $l=$l-(int)((1461*$i)/4)+31;
            $j=(int)((80*$l)/2447);
            $d=$l-(int)((2447*$j)/80);
            $l=(int)($j/11);
            $m=$j+2-12*$l;
            $y=100*($n-49)+$i+$l;
        } else {
            $j=$jd+1402;
            $k=(int)(($j-1)/1461);
            $l=$j-1461*$k;
            $n=(int)(($l-1)/365)-(int)($l/1461);
            $i=$l-365*$n+30;
            $j=(int)((80*$i)/2447);
            $d=$i-(int)((2447*$j)/80);
            $i=(int)($j/11);
            $m=$j+2-12*$i;
            $y=4*$k+$n+$i-4716;
        }
    	
    	
		return array($y, $m, $d); 
    }
    
    /**
     * 
     * @param $y
     * @param $m
     * @param $d
     * @param $h
     * @param $i
     * @param $s
     * @return unknown_type
     */
    function valueToTimeStamp($y,$m,$d,$h,$i,$s) {
	    $y=intval(strval($y));
	    $m=intval(strval($m));
	    $d=intval(strval($d));
	    $h=intval(strval($h));
	    $i=intval(strval($i));
	    $s=intval(strval($s));
	    
	    if ($y<1900) {
	        list($y,$m,$d)=$this->toGregorian($y,$m,$d);
	    }
	    if (!empty($h) or $h!=0) {          
	        $value=strtotime("$y-$m-$d $h:$i:$s");
		} else {
	        $value=strtotime("$y-$m-$d");
		}
		
		return $value;
	}
    
    /**
    * Find num of Day Begining Of Month ( 0 for Sat & 6 for Sun)
    */
    function monthStartDay($month,$day,$year)
    {
        list( $jyear, $jmonth, $jday ) = $this->fromGregorian($year, $month, $day);
        list( $year, $month, $day ) = $this->toGregorian($jyear, $jmonth, "1");
        $timestamp=mktime(0,0,0,$month,$day,$year);
        return $this->phpDate("w",$timestamp);
    }
    
    /**
    * Find days in this year untile now 
    */
    function yearTotalDays($jmonth,$jday,$jyear)
    {
        $year="";
        $month="";
        $year="";
        $result="";
        if($jmonth=="01")
            return $jday;
        for ($i=1;$i<$jmonth || $i==12;$i++)
        {
            list( $year, $month, $day ) = $this->toGregorian($jyear, $i, "1");
            $result+=lastday($month,$day,$year);
        }
        return $result+$jday;
    }
    


    
    /**
    * translate number of month to name of month
    */
    function getWeekName($weekNumber)
    {
		return html_entity_decode($this->_weeksName[$weekNumber]);
    }
    
    /**
     * 
     * @param $weekNumber
     * @return unknown_type
     */
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
    
    function isKabise($year)
    {
        if($year%4==0 && $year%100!=0)
            return true;
        return false;
    }
    /*
    function time()
    {
        return mktime();
    }
    */
    
    /**
     * 
     * @param $hour
     * @param $minute
     * @param $second
     * @param $jmonth
     * @param $jday
     * @param $jyear
     * @return unknown_type
     */
    function makeTime($hour="",$minute="",$second="",$jmonth="",$jday="",$jyear="")
    {
        if(!$hour && !$minute && !$second && !$jmonth && !$jmonth && !$jday && !$jyear) {
            return $this->phpTime();;
		}
        list( $year, $month, $day ) = $this->toGregorian($jyear, $jmonth, $jday);
        $i=mktime($hour,$minute,$second,$month,$day,$year);    
        return $i;
    }
    
    /**
     * 
     * @param $month
     * @param $day
     * @param $year
     * @return unknown_type
     */
    function isDateValid($month,$day,$year) {
    	/*
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        if($month<=12 && $month>0)
        {
            if($j_days_in_month[$month-1]>=$day && $day>0) {
                return 1;
			}
            if(is_kabise($year)) {
                echo "Asdsd";
			}
            if(is_kabise($year) && $j_days_in_month[$month-1]==31) {
                return 1;
			}
        }
        
        return 0;
        */
    }
    
    
    function dateDiff($first,$second) {
    	/*
        $first_date = explode("-",$first);
        $first_date = mktime(0, 0, 0, $first_date[1],$first_date[2], $first_date[0]);
        //echo $first_date[1];
        $second_date = explode("-",$second);
        $second_date = mktime(0, 0, 0,$second_date[1],$second_date[2], $second_date[0]);
        $totalsec=$second_date- $first_date;
        return $totalday = round(($totalsec/86400));
        */
    }
    

    
    
	/**
	* @author 
	* Find Number Of Days In This Month
	*/
	function monthTotalDays($month,$day,$year)
	{
		
		$jday2="";
		$jdate2 ="";
		$lastdayen=$this->phpDate("d",mktime(0,0,0,$month+1,0,$year));
		list( $jyear, $jmonth, $jday ) = $this->fromGregorian($year, $month, $day);
		$lastdatep=$jday;
		$jday=$jday2;
		while($jday2!="1")
		{
			if($day<$lastdayen)
			{
				$day++;
				list( $jyear, $jmonth, $jday2 ) = $this->fromGregorian($year, $month, $day);
				if($jdate2=="1") break;
				if($jdate2!="1") $lastdatep++;
			}
			else
			{ 
				$day=0;
				$month++;
				if($month==13) 
				{
						$month="1";
						$year++;
				}
			}

		}
		return $lastdatep-1;
		
	}
    
        
    /**
    * Find Number Of Days In This Month
    */
    function daysInMonth($monthId){
    	/*
        $daysInMonth = array(
			'31', 
			'31',
			'31',
			'31',
			'31',
			'31',
			'30',
			'30',
			'30',
			'30',
			'30',
			'29'
        );
        return $daysInMonth[$monthId];
        */
    }
    
        

}