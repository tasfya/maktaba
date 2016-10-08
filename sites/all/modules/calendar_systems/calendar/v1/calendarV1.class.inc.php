<?php
/**
* @author Sina Salek
* @changes
* 	
* @todo
* 	- finding and using persian calendar mktime function
* 	- implementing arabic date
* 	- checking to see if pdf function has a better algorithem than the of jdf
*/


define('CMF_CalendarV1_Ok',true);
define('CMF_CalendarV1_Error',2);
define('CMF_CalendarV1_Does_No_Exsists',3);

if (!class_exists('cmfcClassesCoreStandAlone'))
	trigger_error('cmfcCalendarV1:calendarV1 needs cmfcClassesCoreStandAlone packages/cmf/classesCore.class.inc.php',E_USER_ERROR);

class cmfcCalendarV1 extends cmfcClassesCoreStandAlone{
	var $_name;
	var $_language;
	var $_timeZoneName;
	var $_timeZoneInfo;
	
	var $_defaultError=CMF_CalendarV1_Error;
	var $_messagesValue=array(
        CMF_CalendarV1_Ok	=> 'no error',
        CMF_CalendarV1_Error	=> 'unkown error',
        CMF_CalendarV1_Does_No_Exsists	=> 'template "%internalName%" does not exists',
	);
	
	function __construct($options) {
		$this->setOptions($options);
	}
	
	function factory($options) {
		if ($options['name']=='iranian') {
			require_once(dirname(__FILE__).'/calendarSystems/iranian.class.inc.php');
			return new cmfcCalendarV1Iranian($options);
		}
		
		if ($options['name']=='gregorian') {
			require_once(dirname(__FILE__).'/calendarSystems/gregorian.class.inc.php');
			return new cmfcCalendarV1Gregorian($options);
		}
		
		if ($options['name']=='arabic') {
			require_once(dirname(__FILE__).'/calendarSystems/arabic.class.inc.php');
			return new cmfcCalendarV1Arabic($options);
		}
	}
	
	
	function setOption($name,$value,$merge=false) {
		if ($name=='timeZoneOffset') {
			$this->setTimeZoneOffset($value);
		}
		return parent::setOption( $name,$value,$merge);
	}

	function setTimeZoneOffset($offset) {
		/*
		echo timezone_name_from_abbr($countryShortName);
		echo $countryShortName;
		$timezone = new DateTimeZone($countryShortName);
		$this->_timeZoneInfo=reset($timezone->getTransitions());
		cmfcHtml::printr($this->_timeZoneInfo);
		*/
		if (!empty($offset)) {
			list($hour,$minute)=explode(':',$offset);
			$offset=(abs($hour))*60*60+$minute*60;
			if ($hour<0) {
				$offset=$offset*-1;
			}
		}
		$this->_timeZoneInfo['offset']=$offset;
	}
	
	function infoArrayToTimestamp() {
	}
	
	function timestampToInfoArray() {
	}
	
	function strToTimestamp($string) {
	}
	
    function gregorianStrToTimestamp($str) {
    	return strtotime($str);
	}
	
	function timestampToStr($format,$timestamp) {
	}
	
	/**
	* For supporting timezone , use this class date and time functions like phpDate
	*/
	function countDown($infoArray) {
		//$currentDate=$this->timestampToInfoArray();
		$toTimestamp=$this->infoArrayToTimestamp($infoArray);
		$fromTimestamp=$this->phpTime();
				
		$r=$this->dateTimeDiff($toTimestamp,$fromTimestamp);
		$r['toTimestamp']=$toTimestamp;
		$r['fromTimestamp']=$fromTimestamp;
		return $r;
	}	
	
	
	function infoArrayToInfoArray($array) {
		$ts=$this->infoArrayToTimestamp($array);
		return $this->timestampToInfoArray($ts);
	}
	
	function phpGetDate($timestamp=null) {
		if (is_null($timestamp)) {
			if (!empty($this->_timeZoneInfo)) {
				$timestamp=gmmktime()+$this->_timeZoneInfo['offset'];
				$r=array(
					'seconds' => gmdate('s', $timestamp),
					'minutes' => gmdate('i', $timestamp),
					'hours'   => gmdate('H', $timestamp),
					'mday'    => gmdate('d', $timestamp),
					'wday'    => gmdate('w', $timestamp),
					'mon'     => gmdate('m', $timestamp),
					'year'    => gmdate('Y', $timestamp),
					'yday'    => gmdate('z', $timestamp),
					'weekday' => gmdate('l', $timestamp),
					'month'   => gmdate('M', $timestamp),
					'0'       => $timestamp
				);
				
			} else {
				$timestamp=mktime();
				$r=getdate($timestamp);
			}

		} else {
			$r=getdate($timestamp);
		}
		return $r;
	}

    function phpDate($format, $timestamp=null) {
        if (is_null($timestamp) or empty($timestamp)) {
            if (!empty($this->_timeZoneInfo)) {
                $timestamp=gmmktime()+$this->_timeZoneInfo['offset'];
                $r=gmdate($format, $timestamp);
            } else {
                $timestamp=mktime();
                $r=gmdate($format, $timestamp);
            }
        } else {
            if (!empty($this->_timeZoneInfo)) {
                $r=gmdate($format, $timestamp);
            } else {
                $r=date($format, $timestamp);
            }
        }
        return $r;
    }
	
	function phpTime() {
		if (!empty($this->_timeZoneInfo)) {
			$timestamp=gmmktime()+$this->_timeZoneInfo['offset'];
		} else {
			$timestamp=mktime();
		}
		return $timestamp;
	}
	
	function getYmdwMonthAsNavigationalArray($options) {
			
		if (empty($options['columnsHorizontal'])) {
			$options['columnsHorizontal']=6;
		}
		if (empty($options['columnsVertical'])) {
			$options['columnsVertical']=5;
		}
		
		if (isset($options['secondaryCalendar'])) {
			$secondaryCalendar=self::factory(array(
				'name'=>$options['secondaryCalendar']
			));
		}
	    
		$table=array();
		
		$currentMonth=$this->timestampToInfoArray();
		if (is_null($options['year'])) {
			$options['year']=$currentMonth['year'];
		}
		if (is_null($options['month'])) {
			$options['month']=$currentMonth['month'];
		}
		if (is_null($options['day'])) {
			$options['day']=1;
		} else {
			$selectedDay=$options['day'];
		}
		
		$previousMonth=$this->infoArrayToInfoArray(array(
			'year'=>$options['year'],
			'month'=>$options['month']-1,
			'day'=>$options['day'],
		));

		$activeMonth=$this->infoArrayToInfoArray(array(
			'year'=>$options['year'],
			'month'=>$options['month'],
			'day'=>$options['day'],
		));

		
		$nextMonth=$this->infoArrayToInfoArray(array(
			'year'=>$options['year'],
			'month'=>$options['month']+1,
			'day'=>$options['day'],
		));
	
	    
	    $table['currentMonth']=$currentMonth;
	    $table['activeMonth']=$activeMonth;
	    $table['nextMonth']=$nextMonth;
	    $table['previousMonth']=$previousMonth;
		
		foreach ($this->_weeksName as $x=>$weekName) {
		//for ($x=1;$x<=$options['columnsHorizontal'];$x++) {
			$table['weekDays'][$x]=array(
				'number'=>$x,
				'name'=>$weekName,
				'shortName'=>''
			);
		}

		$dayNumber=1;
		$monthStarted=false;
		for ($y=1;$y<=$options['columnsVertical'];$y++) {
			for ($x=0;$x<=$options['columnsHorizontal'];$x++) {
				$value=array();
				//echo $activeMonth['monthFirstDayWeekday'].'-'.$x.'<br />';
				if (($x==$activeMonth['monthFirstDayWeekday'] or $y>1 or $monthStarted==true) and $dayNumber<=$activeMonth['monthDaysNumber']) {
					$monthStarted=true;
					$value['status']=array();
					$value['day']=$dayNumber;
					if ($dayNumber==$selectedDay) {
						$value['status'][]='selected';
					}
					if ($dayNumber==$currentMonth['day'] and $currentMonth['month']==$activeMonth['month'] and $currentMonth['year']==$activeMonth['year']) {
						$value['status'][]='today';
					}
					if (in_array($x,$this->_weekDaysHoliday)) {
						$value['status'][]='holiday';
					}
					
					if (isset($secondaryCalendar)) {
						$__activeMonth=$activeMonth;
						$__activeMonth['day']=$dayNumber;
						$value['secondaryCalendar']=$secondaryCalendar->timestampToInfoArray($this->infoArrayToTimestamp($__activeMonth));
					}
					
					if ($y==5 and $x==6 and $activeMonth['monthDaysNumber']>($dayNumber)) {
						$options['columnsVertical']++;
					}
					$dayNumber++;
				}
				$table['days'][$y][$x]=$value;
			}
		}
		
		return $table;
	}
	
    /**
    * Date    : 15-12-2003.
    *    
    * Ref: Dates go in "2003-12-31".
    * Ref: Times go in "12:59:13".
    * Ref: mktime(HOUR,MIN,SEC,MONTH,DAY,YEAR).
    *   
    * Splits the dates into parts, to be reformatted for mktime.
    * $first_datetime = getdate($first_datetime);
    * $second_datetime = getdate($second_datetime);
    *    
    * makes the dates and times into unix timestamps.
    * $first_unix  = mktime($first_datetime['hours'], $first_time_ex[1], $first_time_ex[2], $first_date_ex[1], $first_date_ex[2], $first_date_ex[0]);
    * $second_unix  = mktime($second_time_ex[0], $second_time_ex[1], $second_time_ex[2], $second_date_ex[1], $second_date_ex[2], $second_date_ex[0]);
    * Gets the difference between the two unix timestamps.
    */
    function dateTimeDiff($endTimestamp,$startTimestamp)
    {
        $timediff = $endTimestamp-$startTimestamp;
        // Works out the days, hours, mins and secs.
        $daysTotal=floor($timediff/(24*60*60));
        $remain=$timediff%(24*60*60);
        $hours=floor($remain/(60*60));
        $remain=$remain%(60*60);
        $mins=floor($remain/(60));
        $remain=$remain%(60);
        $secs=$remain;
        
        // Returns a pre-formatted string. Can be chagned to an array.
        $result['daysTotal']=$daysTotal;
        //$result['days']=$days;
        $result['hours']=$hours;
        $result['minutes']=$mins;
        $result['seconds']=$secs;
        return $result;
    }
    
    
    
    /**
    * convert seconds to days,hours,minuts,seconds as array
    * @param integer $seconds
    * @return array
    */
    function secondsToDays($seconds) {
        $days=intval($seconds/86400);
        $remain=$seconds%86400;
        $hours=intval($remain/3600);
        $remain=$remain%3600;
        $mins=intval($remain/60);
        $secs=$remain%60;
        $r=array(
            'days'=>$days,
            'hours'=>$hours,
            'minutes'=>$mins,
            'seconds'=>$secs
        );
        return $r;
    }
}