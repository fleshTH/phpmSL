


	/*
	 *	mIRC Number Identifiers
	 *
	 *	$rand	$r		$tan	$atan	$sqrt
	 *	$sin	$asin	$round	$log	$int
	 */
	 
	function calc($str='') {
		$r = 0;
		//()+-/*.[0-9]<SPACE>
		if (preg_match('/^[\x28\x29\x2D\x2B\x2F\x2A\x2E\x25\d\s]+$/',$str)) {
        	try {
				var_dump($str);
				eval('try {$r = '.$str.';} catch (Execption $e) { $r = 0; }');
            }
            catch (Exception $e) {
				
            }
         }
		return $r;
	}
	function mirc_rand ($v1='', $v2='') {
    	if (!is_numeric($v1))
			$v1 = substr($v1, 0, 1);
        if (!is_numeric($v2))
        	$v2 = substr($v2, 0, 1);
		if (is_numeric($v1) && is_numeric($v2)) {
			return rand($v1, $v2);
		}
		elseif (!is_numeric($v1) && !is_numeric($v2)) {
			return chr(rand(ord($v1), ord($v2)));
		}
		return NULL; //mIRC returns $null if V1 is numeric and V2 isn't and vice versa
	}
	
	function r ($v1='', $v2='') {
		return self::mirc_rand($v1, $v2);
	}
	
	function mirc_tan ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return tan($num);	
	}
	
	function mirc_atan ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return atan($num);	
	}
	
	function mirc_sqrt ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return sqrt($num);	
	}
	
	function mirc_sin ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return sin($num);
	}
	
	function mirc_asin ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return asin($num);
	}
	
	function mirc_round ($num='', $r = 999) {
		return round($num , $r);
	}
	
	function mirc_log ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return log($num);	
	}
	
	function mirc_int ($num='') {
    	if ($num == '') {
        	return NULL;
        }
		return (int)$num;	
	}
	function mirc_chr($c=0) { 
		return chr($c);
	}

