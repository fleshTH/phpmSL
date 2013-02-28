

    /* 
     *    mIRC Time and Date Identifiers 
     * 
     *    $ctime		$date/$date(parm)		$time/$time(parm) 
     *    $day			$daylight				$fulldate
     *    $ticks		$gmt/$gmt(parm)			$duration 
     *    $uptime(mirc,N)	$idle 
     */ 
      
     /* 
        to do list: 
        $asctime(N,format) 
        $ctime(text) < - $ctime done, text parm is not 
        $timestamp 
        $timestampfmt 
        $ctimer 
        $logstamp 
        $logstampfmt 
        $ltimer 
        $online 
        $timer(N/name) 
     */ 
      
     //for referance ONLY 
/*
    $MIRC_TIME = array( 
                  'yyyy', 'yy',                //years 
                  'mmmm', 'mmm', 'mm', 'm',    //hours 
                  'dddd', 'ddd', 'dd', 'd',    //days 
                  'hh', 'h', 'HH', 'H',        //hours                           
                  'nn', 'n',                //minutes 
                  'ss', 's',                //seconds 
                  'tt',    't', 'TT', 'T',        //am/pm 
                  'oo',                        //English ordinal suffix 
                  'zzz', 'zz', 'z'            //timezone 
                  ); 
    $PHP_TIME = array( 
                'Y', 'y',            //years 
                'F', 'M', 'm', 'n',    //months 
                'l', 'D', 'd', 'j', //days 
                'h', 'g', 'H', 'G', //hours 
                'i', 'i',             //minutes //last one should really be minutes with no leading zeros 
                's','s', //fix for no leading 0s 
                'a',  //needs to be fix for a/p//  'a', 'A', 'A', //needs to be fixed for A/P// 
                'S', 
                'O T', 'O', 'O' //should really have no leading zeros 
                ); 
*/
    function mirc2php () { 
        return array( 
                      'yyyy' => 'Y', 'yy' => 'y',                            //years 
                      'mmmm' => 'F', 'mmm' => 'M', 'mm' => 'm', 'm' => 'n',    //hours 
                      'dddd' => 'l', 'ddd' => 'D', 'dd' => 'd', 'd' => 'j',    //days 
                      'hh' => 'h', 'h' => 'g', 'HH' => 'H', 'H' => 'G',        //hours                           
                      'nn' => 'i', 'n' => 'i',                                //minutes 
                      'ss' => 's', 's' => 's',                                //seconds 
                      'tt' => 'a', 't' => 'a', 'TT' => 'A', 'T' => 'A',        //am/pm 
                      'oo' => 'S',                                            //English ordinal suffix 
                      'zzz' => 'O T', 'zz' => 'O', 'z' => 'O'                //timezone 
                      );     
    } 

    function ctime ($text=null) { 
        if ($text == '') { 
            $output = time(); 
        } 
        else { 
            $output = '';     
        } 
        return $output;     
    } 
     
    function mirc_date ($text='') {
        $mirc_t = self::mirc2php(); 
        if ($text == '') { 
            $output = date('d/m/Y'); 
        } 
        else { 
            $output = ''; 
            for ($x = 0; $x < strlen($text); $x++) { 
                $found = 0; 
                for ($y = 4; $y > 0; $y--) { 
                    if ($mirc_t[substr($text, $x, $y)] != '') { 
                        $output .= date($mirc_t[substr($text, $x, $y)]); 
                        $x += $y - 1; $y = 0; $found = 1; 
                    } 
                } 
                if (!$found) { 
                    $output .= substr($text, $x, 1); 
                } 
            } 
        } 
        return $output;     
    } 
    function mirc_time ($text='') { 
        return self::mirc_date(($text != '' ? $text : 'H:n:ss')); 
    } 
     
    function day ($args=null) { 
        if (isset($args)) 
            return NULL; 
        return date('l'); 
    } 
     
    function daylight ($args=null) { 
        if (isset($args)) 
            return NULL; 
        return (date('I') == 0 ? 0 : 3600); 
    } 
     
    function fulldate ($args=null) { 
        if (isset($args)) 
            return NULL; 
        return date('D M d G:i:s Y'); 
    } 
     
    function ticks ($args=null) { 
        $ticks = posix_times(); 
        return $ticks['ticks']; 
    } 

	/*
		Valid Tests:
			$gmt($ctime) => Tue Sep 08 20:50:39 2009
			$gmt(hh:nn:ss dddd/yyyy) => 08:52:13 Tuesday/2009
            $gmt => 1252444685
	*/
    function gmt ($text=null) { 
        if (isset($text)) { 
        	if (is_numeric($text)) {
				$output = gmdate('D M d H:i:s Y', $text);
			}
			else {
				$mirc_t = self::mirc2php(); 
				$output = ''; 
				for ($x = 0; $x < strlen($text); $x++) { 
					$found = 0; 
					for ($y = 4; $y > 0; $y--) { 
						if ($mirc_t[substr($text, $x, $y)] != '') { 
							$output .= gmdate($mirc_t[substr($text, $x, $y)]); 
							$x += $y - 1; $y = 0; $found = 1; 
						} 
					} 
					if (!$found) { 
						$output .= substr($text, $x, 1); 
					} 
				} 
			}
        } 
        else {  
            $output =  gmdate(time());//(time() + (date('Z') * -1)); 
        } 
         
        return $output; 
    } 
     
     /*
     	$duration(123456789) => 204wks 21hrs 33mins 9secs
		$duration(987654321,2) => 1633wks 4hrs 25mins
     	$duration(123456,3) => 34:17:36
     */
    function duration ($seconds=null, $n=1) { 
    	if ($seconds == '') {
        	return;
        }
        $m = (int)($seconds / 60); $s = $seconds % 60; //seconds / minutes 
        $h = (int)($m / 60); $m = $m % 60;    //hours 
        $d = (int)($h / 24); $h = $h % 24;    //days 
        $w = (int)($d / 7); $d = $d % 7;    //weeks 
        if ($n == 3) { 
			$h = (int)($seconds / 60 / 60);
			$format = str_pad($h,2,"0",STR_PAD_LEFT).':'.str_pad($m,2,"0",STR_PAD_LEFT).':'.str_pad($s,2,"0",STR_PAD_LEFT); 
        } 
        elseif ($n == 2) { 
            $format = ''. 
                        ($w > 1 ? $w.'wks ' : ($w > 0 ? $w.'wk ' : '')). 
                        ($d > 1 ? $d.'days ' : ($d > 0 ? $d.'day ' : '')). 
                        ($h > 1 ? $h.'hrs ' : ($h > 0 ? $h.'hr ' : '')). 
                        ($m > 1 ? $m.'mins ' : ($m > 0 ? $m.'min ' : '')); 
        } 
        else { 
             
            $format = ''. 
                        ($w > 1 ? $w.'wks ' : ($w > 0 ? $w.'wk ' : '')). 
                        ($d > 1 ? $d.'days ' : ($d > 0 ? $d.'day ' : '')). 
                        ($h > 1 ? $h.'hrs ' : ($h > 0 ? $h.'hr ' : '')). 
                        ($m > 1 ? $m.'mins ' : ($m > 0 ? $m.'min ' : '')). 
                        ($s > 1 ? $s.'secs ' : ($s > 0 ? $s.'sec ' : '')); 
        } 
         
        return $format; 
    } 
     
    function uptime ($what='', $n=1) { 
        if ($what == 'mirc') { 
            //linux 
            $uptime = strtok( exec( "cat /proc/uptime" ), "." ); 
            if (!$uptime) { 
                $windows = 1; 
            /* 
                I found this on google, no clue if it works, don't have windows to test: 
                 
                just set $uptime to what/ever seconds in windows 
             
                $winstats = shell_exec("net statistics server"); 
                preg_match("(\d{1,2}/\d{1,2}/\d{4}\s+\d{1,2}\:\d{2}\s+\w{2})", $winstats, $matches); 
                $uptime = time() - strtotime($matches[0]); 
            */ 
                $uptime  = /* windows */0; 
            } 
             
            if ($n == 3) { 
                if ($windows) { 
                    $uptime = (int)($uptime / 1000); 
                } 
            } 
            elseif ($n == 2) { 
                $uptime = self::duration($uptime, 2); 
            } 
        } 
        else { 
            return 0;     
        } 


        return $uptime; 
    } 
    
    
    //we don't have idle
    function idle ($a=null) {
    	return 0;
    }

