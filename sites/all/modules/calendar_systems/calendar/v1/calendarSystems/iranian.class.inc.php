<?php
/**
* @author Sina Salek
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
class cmfcCalendarV1Iranian extends cmfcCalendarV1 {	
	
	var $_dateFormats=array(
		'rfc10'=>'Y-m-d H:i:s',
		'ArraySimple'=>'ÔäÈå 5Çã ÇÑÏíÈåÔÊ 1387'
	);
	
	var $_defaultFormat='rfc10';
	
    var $_monthsName=array(
    	'1'=>'&#1601;&#1585;&#1608;&#1585;&#1583;&#1610;&#1606;',
    	'2'=>'&#1575;&#1585;&#1583;&#1610;&#1576;&#1607;&#1588;&#1578;',
    	'3'=>'&#1582;&#1585;&#1583;&#1575;&#1583;',
    	'4'=>'&#1578;&#1610;&#1585;',
    	'5'=>'&#1605;&#1585;&#1583;&#1575;&#1583;',
    	'6'=>'&#1588;&#1607;&#1585;&#1610;&#1608;&#1585;',
    	'7'=>'&#1605;&#1607;&#1585;',
    	'8'=>'&#1570;&#1576;&#1575;&#1606;',
    	'9'=>'&#1570;&#1584;&#1585;',
    	'10'=>'&#1583;&#1609;',
    	'11'=>'&#1576;&#1607;&#1605;&#1606;',
    	'12'=>'&#1575;&#1587;&#1601;&#1606;&#1583;'
    );
    
    var $_monthsShortName=array(
    	'1'=>'&#1601;&#1585;&#1608;&#1585;&#1583;&#1610;&#1606;',
    	'2'=>'&#1575;&#1585;&#1583;&#1610;&#1576;&#1607;&#1588;&#1578;',
    	'3'=>'&#1582;&#1585;&#1583;&#1575;&#1583;',
    	'4'=>'&#1578;&#1610;&#1585;',
    	'5'=>'&#1605;&#1585;&#1583;&#1575;&#1583;',
    	'6'=>'&#1588;&#1607;&#1585;&#1610;&#1608;&#1585;',
    	'7'=>'&#1605;&#1607;&#1585;',
    	'8'=>'&#1570;&#1576;&#1575;&#1606;',
    	'9'=>'&#1570;&#1584;&#1585;',
    	'10'=>'&#1583;&#1609;',
    	'11'=>'&#1576;&#1607;&#1605;&#1606;',
    	'12'=>'&#1575;&#1587;&#1601;&#1606;&#1583;'
    );
    
    var $_weeksName=array(
    	'6'=>'&#1588;&#1606;&#1576;&#1607;',//Saturday
    	'0'=>'&#1610;&#1603;&#1588;&#1606;&#1576;&#1607;',//Sunday
    	'1'=>'&#1583;&#1608;&#1588;&#1606;&#1576;&#1607;',
    	'2'=>'&#1587;&#1607;&#32;&#1588;&#1606;&#1576;&#1607;',
    	'3'=>'&#1670;&#1607;&#1575;&#1585;&#1588;&#1606;&#1576;&#1607;',
    	'4'=>'&#1662;&#1606;&#1580;&#1588;&#1606;&#1576;&#1607;',
    	'5'=>'&#1580;&#1605;&#1593;&#1607;',
    );
    
    var $_weeksShortName=array(
    	'6'=>'&#1588;',//Saturday
    	'0'=>'&#1610;',//Sunday
    	'1'=>'&#1583;',
    	'2'=>'&#1587;',
    	'3'=>'&#1670;',
    	'4'=>'&#1662;',
    	'5'=>'&#1580;',
    );
        
    var $_meridiemsName=array(
    	'am'=>'&#1602;&#1576;&#1604;&#8207;&#1575;&#1586;&#1592;&#1607;&#1585;',
    	'pm'=>'&#1576;&#1593;&#1583;&#1575;&#1586;&#1592;&#1607;&#1585;',
    );
    
    var $_meridiemsShortName=array(
    	'am'=>'&#1602;&#46;&#1592;',
    	'pm'=>'&#1576;&#46;&#1592;',
    );
    
    var $_weekDaysHoliday=array(6);
    

    
    function timestampToStr($format,$timestamp=null) {
    	if (is_null($timestamp)) {
    		$timestamp=$this->phpTime();
		}
    	return $this->date($format,$timestamp);
	}
    
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
    * This is the simplified versino by Sina Salek
    */
    function date($format,$maket=null)
    {
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
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
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
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                        else $result.=$result1;
                    break;
                case "G":
                    $result1=$this->phpDate("G",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                    case "h":
                    $result1=$this->phpDate("h",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "H":
                    $result1=$this->phpDate("H",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "i":
                    $result1=$this->phpDate("i",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "j":
                    $result1=$jday;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "l":
                    $result1=$this->phpDate("w",$need);
                    $result.=$this->getWeekName($result1);
                    break;
                case "m":
                    if($jmonth<10) $result1="0".$jmonth;
                    else    $result1=$jmonth;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "M":
                    $result.=$this->getMonthShortName($jmonth);
                    break;
                case "n":
                    $result1=$jmonth;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "s":
                    $result1=$this->phpDate("s",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "S":
                    $result.=html_entity_decode("&#1575;&#1605;",ENT_QUOTES,'UTF-8');
                    break;
                case "t":
                    $result.=$this->monthTotalDays ($month,$day,$year);
                    break;
                case "w":
                    $result1=$this->phpDate("w",$need);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "y":
                    $result1=substr($jyear,2,4);
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
                    else $result.=$result1;
                    break;
                case "Y":
                    $result1=$jyear;
                    if($transnumber==1) $result.=cmfcString::convertNumbersToFarsi($result1);
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
            

    function fromGregorian ($g_y, $g_m, $g_d) 
    {
        if ($g_y<1300 or $g_m<1 or $g_d<1) return array('','','');
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);     
        
       $gy = $g_y-1600; 
       $gm = $g_m-1; 
       $gd = $g_d-1; 

       $g_day_no = 365*$gy+div($gy+3,4)-div($gy+99,100)+div($gy+399,400); 

       for ($i=0; $i < $gm; ++$i) 
          $g_day_no += $g_days_in_month[$i]; 
       if ($gm>1 && (($gy%4==0 && $gy%100!=0) || ($gy%400==0))) 
          /* leap and after Feb */ 
          $g_day_no++; 
       $g_day_no += $gd; 

       $j_day_no = $g_day_no-79; 

       $j_np = div($j_day_no, 12053); /* 12053 = 365*33 + 32/4 */ 
       $j_day_no = $j_day_no % 12053; 

       $jy = 979+33*$j_np+4*div($j_day_no,1461); /* 1461 = 365*4 + 4/4 */ 

       $j_day_no %= 1461; 

       if ($j_day_no >= 366) { 
          $jy += div($j_day_no-1, 365); 
          $j_day_no = ($j_day_no-1)%365; 
       } 

       for ($i = 0; $i < 11 && $j_day_no >= $j_days_in_month[$i]; ++$i) 
          $j_day_no -= $j_days_in_month[$i]; 
       $jm = $i+1; 
       $jd = $j_day_no+1; 

       return array($jy, $jm, $jd); 
    } 

    function toGregorian($j_y, $j_m, $j_d) 
    {
        $j_d = (int) $j_d;
        $j_m = (int) $j_m;
        $j_y = (int) $j_y;
        
        
        $g_days_in_month = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); 
        $j_days_in_month = array(31, 31, 31, 31, 31, 31, 30, 30, 30, 30, 30, 29);
        
        #--(Begin)-->By Sina
        if ($j_m>12) {
        	$j_y=$j_y+(floor($j_m/12));
        	$j_m=$j_m%12;
		}
        #--(End)-->By Sina
        
        
        if ($j_d < 1)
            $j_d = 1;
        elseif ($j_d > $j_days_in_month[ $j_m - 1 ])
            $j_d = $j_days_in_month[ $j_m - 1 ];
        
        
        $jy = $j_y-979; 
        $jm = $j_m-1;
        $jd = 0;
        $jd = $j_d - 1; 
        
        $j_day_no = 365*$jy + div($jy, 33)*8 + div($jy%33+3, 4); 
        for ($i=0; $i < $jm; ++$i) 
          $j_day_no += $j_days_in_month[$i]; 
        
        $j_day_no += $jd; 
        
        $g_day_no = $j_day_no+79; 
        
        $gy = 1600 + 400*div($g_day_no, 146097); /* 146097 = 365*400 + 400/4 - 400/100 + 400/400 */ 
        $g_day_no = $g_day_no % 146097; 
        
        $leap = true; 
        if ($g_day_no >= 36525) /* 36525 = 365*100 + 100/4 */ 
        { 
          $g_day_no--; 
          $gy += 100*div($g_day_no,  36524); /* 36524 = 365*100 + 100/4 - 100/100 */ 
          $g_day_no = $g_day_no % 36524; 
        
          if ($g_day_no >= 365) 
             $g_day_no++; 
          else 
             $leap = false; 
        } 
        
        $gy += 4*div($g_day_no, 1461); /* 1461 = 365*4 + 4/4 */ 
        $g_day_no %= 1461; 
        
        if ($g_day_no >= 366) { 
          $leap = false; 
        
          $g_day_no--; 
          $gy += div($g_day_no, 365); 
          $g_day_no = $g_day_no % 365; 
        } 
        
        for ($i = 0; $g_day_no >= $g_days_in_month[$i] + ($i == 1 && $leap); $i++) 
          $g_day_no -= $g_days_in_month[$i] + ($i == 1 && $leap); 
        $gm = $i+1; 
        $gd = $g_day_no+1; 
        return array($gy, $gm, $gd); 
    }
    
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
    * @desc accept array,timestamp and string as input datetime in jalali or gregorian format
    */
    function smartGet($type,$value="now") {
        
        if ($value!='now') {
            $value=$this->strtotime($value);
        }
        
        if (empty($value)) return;
        
        return $this->date($type,$value);
    }
        
    
    /**
    * translate number of month to name of month
    */
    function getWeekName($weekNumber)
    {
		return html_entity_decode($this->_weeksName[$weekNumber],ENT_QUOTES,'UTF-8');
    }
    
    function getWeekShortName($weekNumber)
    {
		return html_entity_decode($this->_weeksShortName[$weekNumber],ENT_QUOTES,'UTF-8');
    }
        
    /**
    * translate number of month to name of month
    */
    function getMonthName($month)
    {
		return html_entity_decode($this->_monthsName[$month],ENT_QUOTES,'UTF-8');
    }
    
    function getMonthShortName($month)
    {
        return html_entity_decode($this->_monthsShortName[$month],ENT_QUOTES,'UTF-8');
    }
    
    function getMeridiemName($m)
    {
        return html_entity_decode($this->_meridiemsName[$m],ENT_QUOTES,'UTF-8');
    }
    
    function getMeridiemShortName($m)
    {
        return html_entity_decode($this->_meridiemsShortName[$m],ENT_QUOTES,'UTF-8');
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
    
    function makeTime($hour="",$minute="",$second="",$jmonth="",$jday="",$jyear="")
    {
        if(!$hour && !$minute && !$second && !$jmonth && !$jmonth && !$jday && !$jyear) {
            return $this->phpTime();;
		}
        list( $year, $month, $day ) = $this->toGregorian($jyear, $jmonth, $jday);
        $i=mktime($hour,$minute,$second,$month,$day,$year);    
        return $i;
    }
    
    
    function isDateValid($month,$day,$year) {
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
    * Find Number Of Days In This Month
    */
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
    function daysInMonth($monthId, $ctype = 'gregorian'){
        $daysInMonth = array(
            'jalali' => array(
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
            ),
            'gregorian' => array(
                '31', 
                '28',
                '31',
                '30',
                '31',
                '30',
                '31',
                '31',
                '30',
                '31',
                '30',
                '31'
            )
        );
        return $daysInMonth[$ctype][$monthId];
    }
    
    
    /**
    * here convert to number in persian
    */
    function convertNumbersToFarsi($srting,$mode='html')
    {
        if ($mode=='html') {
            $num0="&#1776;";
            $num1="&#1777;";
            $num2="&#1778;";
            $num3="&#1779;";
            $num4="&#1780;";
            $num5="&#1781;";
            $num6="&#1782;";
            $num7="&#1783;";
            $num8="&#1784;";
            $num9="&#1785;";
        } elseif($mode=='plainText') {
            $num0=chr(hexdec('DB')).chr(hexdec('B0'));
            $num1=chr(hexdec('DB')).chr(hexdec('B1'));
            $num2=chr(hexdec('DB')).chr(hexdec('B2'));
            $num3=chr(hexdec('DB')).chr(hexdec('B3'));
            $num4=chr(hexdec('DB')).chr(hexdec('B4'));
            $num5=chr(hexdec('DB')).chr(hexdec('B5'));
            $num6=chr(hexdec('DB')).chr(hexdec('B6'));
            $num7=chr(hexdec('DB')).chr(hexdec('B7'));
            $num8=chr(hexdec('DB')).chr(hexdec('B8'));
            $num9=chr(hexdec('DB')).chr(hexdec('B9'));
        }

        $stringtemp="";
        $len=strlen($srting);
        for($sub=0;$sub<$len;$sub++) {
            if(substr($srting,$sub,1)=="0")$stringtemp.=$num0;
            elseif(substr($srting,$sub,1)=="1")$stringtemp.=$num1;
            elseif(substr($srting,$sub,1)=="2")$stringtemp.=$num2;
            elseif(substr($srting,$sub,1)=="3")$stringtemp.=$num3;
            elseif(substr($srting,$sub,1)=="4")$stringtemp.=$num4;
            elseif(substr($srting,$sub,1)=="5")$stringtemp.=$num5;
            elseif(substr($srting,$sub,1)=="6")$stringtemp.=$num6;
            elseif(substr($srting,$sub,1)=="7")$stringtemp.=$num7;
            elseif(substr($srting,$sub,1)=="8")$stringtemp.=$num8;
            elseif(substr($srting,$sub,1)=="9")$stringtemp.=$num9;
            else $stringtemp.=substr($srting,$sub,1);
        }
        return $stringtemp;
    }


    
    /**
    * $replacements['00username00']='jafar gholi';
    */
    function replaceVariables($replacements,$text) {
        foreach ($replacements as $needle=>$replacement) {
            $text=str_replace($needle,$replacement,$text);
        }
        return $text;
    }

}