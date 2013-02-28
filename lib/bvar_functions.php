
	/* identifers */
	/* &bvars are pointless since there is no limit in 
 	   php and i'm not going to add a limit. But, since
	   mIRC has a limit, i might as well add bvar support
	   also, it works with binary data, though most people
	   only use it to get around the limit on variables
	*/
	function bvar($bvar,$start,$length = null) { 
		$localParams = $this->parent->getLocalParams();
		$p = $localParams['prop'];
		if (!isset($this->parent->_scope['bvars'][$bvar])) return;
		$bt = $this->parent->_scope['bvars'][$bvar];
		if ($start == 0) { 
			return strlen($bt);
		}
		if (!$length) { 
			if (is_numeric($start)) { 
				$out = substr($bt,$start-1,1);
			}
			else if (strpos($start,"-") !== false) { 
				if (substr($start,-1,1) == "-") { 
					$out = substr($bt,intval($start)-1);
				}
			}
		}
		else { 
			$out = substr($bt,$start-1,$length);
		}
		if ($p == "text") { 
			return $out;
		}
		else { 
			return $this->_strToAscii($out);
		}
	}
	function bfind($bvar,$pos,$str) {
		return (($p = stripos($this->parent->_scope['bvars'][$bvar],$str,$pos-1)) !== false)?$p+1:'';
	}
	function mirc_bset($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		$bvar = array_shift($args);
		$n = array_shift($args);
		if (stripos($switch,"t") !== false) { 
			$text = implode(" ",$args);			
		}
		else { 
			$text = $this->_asciiToStr(implode(" ",$args));
		}
		if (isset($this->parent->_scope['bvars'][$bvar])) { 
			$this->parent->_scope['bvars'][$bvar] = substr($this->parent->_scope['bvars'][$bvar],0,$n-1) . $text . substr($this->parent->_scope['bvars'][$bvar],$n-1+strlen($text));
		}
		else { 
			$this->parent->_scope['bvars'][$bvar] = (($n-1)?$this->_asciiToStr(str_repeat("00 ",$n-1)):"") . $text;
		}
	}
	function _strToAscii($str) { 
		$x = str_split($str);
		array_walk($x,create_function('&$item','$item = ord($item);'));
		return implode(" ",$x);
	}
	function _asciiToStr($ascii) {
		$x = explode(" ",$str);
		array_walk($x,create_function('&$item','$item = chr($item);'));
		return implode("",$x);
	}
