


	/*
	 *	mIRC Text and Identifiers
	 *
	 *	$len	$upper	$lower	$left	$right
	 *	$qt		$noqt	$
	 */
	/*
		To Do:
	
			$abs(N)
			$and(A,B)
			$asc(C)
			$base(N,inbase,outbase,zeropad,precision)
			$biton(A,N)
			$bitoff(A,N)
			-$bytes(N,bkmgt3d)
			-$calc(operations)
			x$ceil(N)
			x$chr(N)
			$compress(file|&bvar, blN)
			$decompress(file|&bvar)
			$cos(N), $acos(N)
			$count(string,substring,substring2,...,substringN)
			$countcs()
			x$encode(%var | &binvar, mubt, N)
			x$decode(%var | &binvar, mubt, N)
			x$floor(N)
			$isbit(A,N)
			x$islower(text)
			x$isupper(text)
			x$longip(address)
			x$mid(text,S,N)
			$not(A)
			$or(A,B)
			$ord(N)
			$pos(text,string,N)
			$poscs()
			x$remove(string,substring,...)
			x$removecs()
			x$replace(string,substring,newstring,...)
			x$replacecs()
			$replacex(string,substring,newstring,...)
			$str(text,N)
			$strip(text,burcmo)
			$stripped
			$wrap(text, font, size, width, [word,] N)
			$xor(A,B)
	*/
	function md5($str) { 
		return md5($str);
	}
function base($val,$from,$to,$pad = 0,$precision = 12) { /* thanks to ramirez for decimal support */
        $pos = strpos($val, '.');
        if ($pos !== false) {
                $dec = substr($val,$pos+1);
                $val = substr($val,0,$pos);
        } else {
                $dec = 0;
                $val = $val;
        }
        $out = base_convert($val,$from,$to);
        $out = str_pad($out,$pad,"0",STR_PAD_LEFT);
        if ($dec) {
                $dec = base_convert($dec,$from,10);
                for (;(int)$dec;$dec /= $from);
                $out .= '.';
                for ($i = 0; $dec && $i < $precision; $i++) {
                        $dec *= $to;
                        $num = (int)$dec;
                        $dec -= $num;
                        $out .= base_convert($num,10,$to);
                }
        }
        return strtoupper($out);
}
	function debug($str) { 
		$r = "";
		eval("\$r = $str;");
		return $r;
	}
	function len($str) { 
		return strlen($str);
	}
	
	function lower($str) { 
		return strtolower($str);
	}
	function floor($float) { 
		return floor($float);
	}
	function ceil($float) { 
		return ceil($float);
	}
	function bytes($bytes,$switch="") { 
		$localParams = $this->parent->getLocalParams();
		$p = $localParams['prop'];
		$suffix = "";
		 $units = array('B', 'KB', 'MB', 'GB', 'TB'); 
                if (in_array(Array("b","k","m","g","t"),str_split(strtolower($switch))) === false || strstr($switch,"3") !== false) { 
   			 $bytes = max($bytes, 0); 
			 $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
			 $pow = min($pow, count($units) - 1); 
	   		 $bytes /= pow(1024, $pow);
			 $suffix = $units[$pow];
		}
		else if (stristr($switch,"t") !== false) { 
			$suffix = "TB";
			$bytes /= pow(1024,4);
		}
		else if (stristr($switch,"g") !== false) { 
			$suffix = "GB";
			$bytes /= pow(1024,3);
		}
		else if (stristr($switch,"m") !== false) { 
			$suffix = "MB";
			$bytes /= pow(1024,2);
		}

		else if (stristr($switch,"k") !== false) { 
			$suffix = "KB";
			$bytes /= 1024;
		}

		else if (stristr($switch,"b") !== false) { 
			$suffix = "B";
		}
		
		if (strstr($switch,"3") !== false) { 
			if (strstr($bytes,".") === false) { 
				$bytes .= ".00";
			}
			else if (strlen($bytes) < 3) { 
				$bytes .= "0";
			}
			else {
				$bytes = round($bytes,2);
			}
			
		}
		else if (strstr($switch,"d") !== false) { 
			$bytes = round($bytes,6);
		}
		else {
			$bytes = round($bytes,2);
		}
   

		$bytes = preg_replace('/\B(?=(...)+$)/',",",$bytes);
		if ($p == "suf") { 
			$bytes .= $suffix;
		}
		return $bytes;
	}
	function strip($str,$switch = "bcur") { 
		$i = 0;
		if (stristr($switch,"b") !== false) { 
			$i++;
			$str = str_replace(chr(2),"",$str);
		}
		if (stristr($switch,"u") !== false) { 
			$i++;
			$str = str_replace(chr(31),"",$str);
		}
		if (stristr($switch,"c") !== false) { 
			$i++;
			$str = preg_replace('/\x03((\d{1,2)(,\d{1,2})?)?/',"",$str);
		}
		if (stristr($switch,"r") !== false) { 
			$i++;
			$str = str_replace(chr(22),"",$str);
		}
		if ($i == 4) { 
			$str = str_replace(chr(15),"",$str);
		}
		return $str;
	}
	function upper($str) {
		return strtoupper($str);
	}
	function longip($ip) { 
		return ip2long($ip);
	}
	function left($str,$n) { 
		if ($n > 0) { 
			return substr($str,0,$n);
		}
		else if ($n < 0) { 
			return substr($str,0,strlen($str) + $n);
		}
		else { 
			return "";
		}
	}
	function encode($str,$switch = "ut",$chuck = 0) { 
		if (stristr($switch,"u") !== false) { 
			return convert_uuencode($str);
		}
		else if (stristr($switch,"m")) { 
			return base64_encode($str);
		}
	}
	function decode($str,$switch = "ut",$chuck = 0) { 
		if (stristr($switch,"u") !== false) { 
			return convert_uudecode($str);
		}
		else if (stristr($switch,"m")) { 
			return base64_decode($str);
		}
	}

	function right($str,$n) { 
		if ($n > 0) { 
			return substr($str,-1*$n);
		}
		else if ($n < 0) { 
			return substr($str,-1*$n,strlen($str));
		}
		else {
			return "";
		}
	}
	
	
	function qt ($text) {
		return (substr($text,0,1) == '"' ? '' : '"').$text.(substr($text,-1,1) == '"' ? '' : '"');
	}
	
	function noqt ($text) {
		if (substr($text,0,1) == '"') {
			$text = substr($text,1);	
		}
		if (substr($text,-1,1) == '"') {
			$text = substr($text,0,-1);	
		}
		return $text;
	}
	function true() {
		return '$true';
	}
	function false() { 
		return '$false';
	}
	function isupper ($text) {
		return (strtoupper($text) === $text ? '$true' : '$false');
	}
	
	function islower ($text) {
		return (strtolower($text) === $text ? '$true' : '$false');
	}
	function replace() { 
		$x = func_get_args();
		$str = "";
		$f = Array();
		$r = Array();
		if (count($x) >= 3) { 
			$str = array_shift($x);
			while (count($x)) {
				$f[] = array_shift($x);
				$r[] = array_shift($x);
			}

			return str_ireplace($f,$r,$str);
		}
	}
	function replacex() { 
		$x = func_get_args();
		$str = "";
		$rep = Array();
		if (count($x) > 3) { 
			$str = array_shift($x);
			if ((count($x) % 2) == 0) { 
				while (count($x)) { 
					$rep[array_shift($x)] = array_shift($x);
				}
				return strtr($rep,$str);
			}
		}
	}
	function remove() { 
		$x = func_get_args();
		$str = "";
		$rem = Array();
		$str = array_shift($x);
		if (count($x)) return str_replace($x,"",$str);
	}
	function replacecs() { 
		$x = func_get_args();
		$str = "";
		$f = Array();
		$r = Array();
		if (count($x) > 3) { 
			$str = array_shift($x);
			while (count($x)) {
				$f[] = array_shift($x);
				$r[] = array_shift($x);
			}

			return str_replace($f,$r,$str);
		}
	}
	function mid($str,$s,$n) { 
		if ($n < 0) return;
		if ($s > 0) { 
			return substr($str,$s-1,$n);
		}
		else { 
			return substr($str,$s,$n);
		}
	}
	function str($str,$n) { 
		return str_repeat($str,$n);
	}

	function mirc_echo($args) {
		echo implode(" ",$args) . "<br>"; 
	}
	function mirc_var($args) { 
		$_scope = &$this->parent->_localvars[$this->parent->l_stack];
		$v = array_shift($args);
		if ($v[0] == "%") { 
			$x = $_scope[$v] = trim(preg_replace('/^=/','',implode(" ",$args)));
		}
	}
	function mirc_eval($str) { 
		return $this->parent->execLine($str);
	}
	function mirc_inc($args) { 
		$_scope = &$this->parent->_localvars[$this->parent->l_stack];
		list($var,$n) = $args;
        	if ($n == null) {
			$n = 1;
		}
		if (empty($_scope[$var])) { 
			$_scope[$var] = $n;
		}
		else { 
			$_scope[$var] += $n;
		}
	}
	function mirc_dec($args) { 
		$_scope = &$this->parent->_localvars[$this->parent->l_stack];

		list($var,$n) = $args;
        	if ($n == null) {
			$n = -1;
		}
		if (empty($_scope[$var])) { 
			$_scope[$var] = $n;
		}
		else { 
			$_scope[$var] += $n;
		}
	}
	function mirc_set($args) { 
		if ($args[0][0] == "%") { 
			mSL::$variables[array_shift($args)] = implode(" ",$args);
		}
	}
	function mirc_unset($args) { 
		if ($args[0][0] == "%") { 
			unset(mSL::$variables[$args[0]]);
		}	
	
	}
