

/*************************************************************************

 Tokens

**************************************************************************/




function tokens($str,$tokenizer) { 
	$t = "/". str_replace("/","\/",preg_quote(chr($tokenizer))) ."+/i";
	return preg_split($t,$str);
}
function gettok($str,$token,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($token == 0) return count($arr);
	if ($token < 0) { 
		return $arr[count($arr) + $token];
	}
	$n = preg_split('/(?!^-)(?<!-)-/',$token);
	if (isset($n[1]) && empty($n[1])) { 
		$n[1] = -1;
	}	
	if (count($n) > 1) { 
		$n[0] = ((int) $n[0]);
		$n[1] = ((int) $n[1]);
		if ($n[0] < 0) { 
			$n[0] = count($arr)  + $n[0];
		}
		if ($n[1] < 0) { 
			$n[1] = count($arr) + 1 + $n[1];
		}
		if ($n[0] > $n[1]) { 
			$n = array_reverse($n);
			$n[1]++;
		}

		$n[0]--;	
		$n[1] = ($n[1]  - $n[0]);



		return implode(chr($tokenizer),array_splice($arr,$n[0],$n[1]));
	}       
	
	
	if ($token > 0) { 
		return $arr[$token-1];
	}
	
}
function addtok($str,$val,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	foreach ($arr as $v) { 
		if ($val == $v) { 
			return $str;
		}
	}
	$arr[] = $val;
	return implode(chr($tokenizer),$arr);
}

function numtok($str,$tokenizer) { 
	if ($str == "") return 0;
	return count($this->tokens($str,$tokenizer));
	
}

function remtok($str,$find,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($t = $this->findtok($str,$find,$n,$tokenizer)) { 
		unset($arr[$t-1]);
	}
	return implode(chr($tokenizer),$arr);
}
function remtokcs($str,$find,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($t = $this->findtokcs($str,$find,$n,$tokenizer)) { 
		unset($arr[$t-1]);
	}
	return implode(chr($tokenizer),$arr);
}
function reptok($str,$find,$new,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($t = $this->findtok($str,$find,$n,$tokenizer)) { 
		$arr[$t-1] = $new;
	}
	return implode(chr($tokenizer),$arr);
}
function reptokcs($str,$find,$new,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($t = $this->findtokcs($str,$find,$n,$tokenizer)) { 
		$arr[$t-1] = $new;
	}
	return implode(chr($tokenizer),$arr);
}


function instok($str,$to,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($n <= 0) { 
		$n = count($arr) + $n;
	}
	else { 
		$n = $n - 1;
	}
	if ($n > count($arr)) { 
		$n = count($arr);
	}
	$t =  array_slice($arr,0,$n);$x = array_slice($arr,$n);  $s = array_merge($t,Array($to),$x);
	return implode(chr($tokenizer),$s);
}


function findtok($str,$find,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$arr2 = $arr;
	array_walk($arr2,create_function('&$item',' $item = strtolower($item);  '));
	$x = array_keys($arr2,strtolower($find));
	if ($n == 0) return count($x);
	if ($x[$n-1] !== null) return $x[$n-1] + 1;

}
function findtokcs($str,$find,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$x = array_keys($arr,$find);
	if ($n == 0) return count($x);
	if ($x[$n-1] !==  null) return $x[$n-1] + 1;

}



function istok($str,$find,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	array_walk($arr,create_function('&$item',' $item = strtolower($item);'));
	return (in_array(strtolower($find),$arr))?'$true':'$false';
}
function istokcs($str,$find,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	return (in_array($find,$arr))?'$true':'$false';
}
function puttok($str,$new,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	if ($n < 0) $n = count($arr) + 1 + $n;
	if ($n == 0 || $n > count($arr)) { return $str; }
	$arr[$n-1] = $new;
	return implode(chr($tokenizer),$arr);
}

function sorttok($str,$tokenizer,$switch="a") { 
	$arr = $this->tokens($str,$tokenizer);
	$arr2 = $arr;
	array_walk($arr2,create_function('&$item','$item = strtolower($item);'));
	if (stristr($switch,"a") !== false) { 
		asort($arr2);
		print_r($arr2);
		print_r($arr);
		$arr = array_replace($arr2,$arr);
		if (stristr($switch,"r") !== false) { 
			$arr = array_reverse($arr);
		}
	}
	else if (stristr($switch,"n") !== false) { 
		$n = $arr2;
		$a = Array();
		foreach ($n as $k => $v) { 
	 		if (preg_match("/^(-?[\d]+)/",$v,$m)) { $n[$k] = $m[1]; } else { $n[$k] = "";$a[] = $v;  }
		}
	
		uasort($n,create_function('$a,$b','if (($a * 1) === ($b * 1)) return 0; return (($a * 1) < ($b * 1)) ? -1 : 1;'));
		if (stristr($switch,"r") !== false) { 
			$n = array_reverse($n,true);
		}
		$t = Array();
		foreach($n as $k => $v) { 
			if ($v == "") $t[] = array_shift($a);
			else $t[] = $arr[$k];
		}
		$arr = array_replace($t,$arr);
	}
	else if (stristr($switch,"c") !== false) { 
		uasort($arr2,create_function('$a,$b','$c = Array("@" => 500, "%" =>501, "+" => 502); if ((!isset($c[$a[0]]) && !isset($c[$a[0]])) ||$c[$a[0]] == $c[$b[0]]) { if ($a == $b) { return 0; } return ($a < $b) ? -1 : 1; } return (!isset($c[$a[0]]))?1:(!isset($c[$b[0]]))?-1:($c[$a[0]] < $c[$b[0]]) ? -1 : 1;'));
		$arr = array_replace($arr2,$arr);
		if (stristr($switch,"r") !== false) { 
			$arr = array_reverse($arr);
		}
	}
	else if (stristr($switch,"r") !== false) { 
			asort($arr2);
			$arr = array_replace($arr2,$arr);
			$arr = array_reverse($arr);
	}
	

	return implode(chr($tokenizer),$arr);

}
function sorttokcs($str,$tokenizer,$switch="a") { 
	$arr = $this->tokens($str,$tokenizer);
	if (stristr($switch,"a") !== false) { 
		asort($arr);
		if (stristr($switch,"r") !== false) { 
			$arr = array_reverse($arr);
		}
	}
	else if (stristr($switch,"n") !== false) { 
		$n = $arr;
		$a = Array();
		foreach ($n as $k => $v) { 
	 		if (preg_match("/^(-?[\d]+)/",$v,$m)) { $n[$k] = $m[1]; } else { $n[$k] = "";$a[] = $v;  }
		}
	
		uasort($n,create_function('$a,$b','if (($a * 1) === ($b * 1)) return 0; return (($a * 1) < ($b * 1)) ? -1 : 1;'));
		if (stristr($switch,"r") !== false) { 
			$n = array_reverse($n,true);
		}
		$t = Array();
		foreach($n as $k => $v) { 
			if ($v == "") $t[] = array_shift($a);
			else $t[] = $arr[$k];
		}
		$arr = $t;
	}
	else if (stristr($switch,"c") !== false) { 
		uasort($arr2,create_function('$a,$b','$c = Array("@" => 500, "%" =>501, "+" => 502); if ((!isset($c[$a[0]]) && !isset($c[$a[0]])) ||$c[$a[0]] == $c[$b[0]]) { if ($a == $b) { return 0; } return ($a < $b) ? -1 : 1; } return (!isset($c[$a[0]]))?1:(!isset($c[$b[0]]))?-1:($c[$a[0]] < $c[$b[0]]) ? -1 : 1;'));
		if (stristr($switch,"r") !== false) { 
			$arr = array_reverse($arr);
		}
	}
	else if (stristr($switch,"r") !== false) { 
			sort($arr2);
			$arr = array_reverse($arr);
	}
	

	return implode(chr($tokenizer),$arr);

}



function deltok($str,$token,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$n = preg_split('/(?!^-)(?<!-)-/',$token);
	if (count($n) > 1) { 
		$n[0] = ((int) $n[0]);
		$n[1] = ((int) $n[1]);
		if ($n[0] < 0) { 
			$n[0] = count($arr)  + $n[0];
		}
		if ($n[1] < 0) { 
			$n[1] = count($arr) + 1 + $n[1];
		}
		if ($n[0] > $n[1]) { 
			$n = array_reverse($n);
			$n[1]++;
		}

		$n[0]--;	
		$n[1] = ($n[1]  - $n[0]);

		array_splice($arr,$n[0],$n[1]);

		return implode(chr($tokenizer),$arr);
	}       
	if ($token == 0) return implode(chr($tokenizer),$arr);
	else if ($token > 0) { 
		unset($arr[$token-1]);
		return implode(chr($tokenizer),$arr);
	}
	else if ($token < 0) { 
		unset($arr[count($arr) + $token]);
		return implode(chr($tokenzer),$arr);
	}
	

}

function wildtok($str,$match,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$t = Array();
	foreach($arr as $v) { 
		if ($this->parent->isWildCardMatch($v,$match)) { 
			$t[] = $v;
		}
	}
	if ($n == 0) return count($t);
	return $t[$n-1];
}
function wildtokcs($str,$match,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$t = Array();
	foreach($arr as $v) { 
		if ($this->parent->isWildCardMatch($v,$match,1)) { 
			$t[] = $v;
		}
	}
	if ($n == 0) return count($t);
	return $t[$n-1];
}
function matchtok($str,$match,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$t = Array();
	foreach($arr as $v) { 
		if (stristr($v,$match) !== false) { 
			$t[] = $v;
		}
	}
	if ($n == 0) return count($t);
	return $t[$n-1];
}
function matchtokcs($str,$match,$n,$tokenizer) { 
	$arr = $this->tokens($str,$tokenizer);
	$t = Array();
	foreach($arr as $v) { 
		if (strstr($v,$match) !== false) { 
			$t[] = $v;
		}
	}
	if ($n == 0) return count($t);
	return $t[$n-1];
}

/**************************************************************

   End Tokens

**************************************************************/

