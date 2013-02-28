

	/*
	 *	mIRC Regular Expressions
	 *
	 *	$regex	$regml
	 */
	 
	 
	 /*
	 	To Do:
			- $regsubex
			- $regsub
		*/
	 
	 

	 
	 
	function regex($str,$patt,$name =",default") { 
		global $regex_names;
		   if ($name != ",default") { 
			list($str,$patt,$name) = Array($patt,$name,$str);
			
		}
		$regObj = $this->regex_fix($patt);
		$modifers = $regObj->modifiers;
		if (strstr($modifiers,"S") !== false) { 
			$modifiers = str_replace("S","",$modifiers);
			$str = $this->strip($str);
		}
		$regex = $regObj->regex . $modifers;
		$s = preg_match($regex,$str,$m);
		$regex_names[$name] = $m;
		return $s;
	}
	
	function regsubex($str,$patt,$rep,$name = ",default") {
		global $regex_names;
		   if ($name != ",default") { 
			list($name,$str,$patt,$rep) = Array($str,$patt,$rep,$name);
		}
		$regObj = $this->regex_fix($patt);
		$modifiers = $regObj->modifiers;
		if (strstr($modifiers,"S") !== false) { 
			$modifiers = str_replace("S","",$modifiers);
			$str = $this->strip($str);
		}
		$l = 1;
		if (strstr($modifiers,"g") !== false) { 
			$l = -1;
			$modifiers = str_replace("g","",$modifiers);
		}
		$modifiers .= 'e';
		var_dump($rep);
		$regex = $regObj->regex . $modifiers;
		$rep =  "\$this->parent->execLine('". $rep ."')";
		//$s = preg_match($regex,$str,$m);
		return preg_replace($regex,$rep,$str,$l);
		
	
	}
	
	function regml($n,$name = ",default") { 
		global $regex_names;
		   if ($name != ",default") { 
			list($str,$patt,$name) = Array($patt,$name,$str);
			
		}
		if ($n == 0) { return count($regex_names[$name][1]); }
		return $regex_names[$name][$n];
	}
	private function regex_fix($regex) { 
		$r = new StdClass;
		if (preg_match('/(\/.*?(?<!(?<!\x5C)\x5C)\/)(.*)$/',$regex,$m)) {
			$r->regex = $m[1];
			$r->modifiers = $m[2];
		}
		else { 
			$r->regex = "/$regex/";
		}
		return $r;
	}

