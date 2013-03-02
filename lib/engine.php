
<?php
	/**********************************
	 *	      mIRC Interpreter	      *
	 *                                *
	 **********************************/
	error_reporting(E_ALL ^ E_NOTICE);
	//error_reporting(0);
	set_time_limit(0);
	
	//mIRC Idents Libraries
/*
	include_once('time_date_identifiers.php');
	include_once('text_identifiers.php');
	include_once('token_identifiers.php');
	include_once('regular_expressions.php');
	include_once('number_identifiers.php');
	include_once('conditionals.php');
*/
	include_once('timers.php');
function ms_start() { 
	$starttime = microtime();
	$startarray = explode(" ", $starttime);
	$starttime = $startarray[1] + $startarray[0];
	return $starttime;
}
function ms_end($starttime) { 
	$endtime = microtime();
	$endarray = explode(" ", $endtime);
	$endtime = $endarray[1] + $endarray[0];
	$totaltime = $endtime - $starttime; 
	$totaltime = round($totaltime,5);
	return $totaltime;
}

Class mSL {
	public $functions;
	static public $aliases = Array();
	static public $variables = Array();
	static public $hash_tables = Array();
	static public $events = Array();
	static public $sockets  = Array();
	static public $bvar = Array();
	public $_connectionSettings = Array();
	public $_scope = Array();
	private $stackNumber;
	private $localParams;
	public $_localvars = Array();
	public $l_stack = 0;
	public $stream;
	private $instack = 0;

	

	function __construct() {
		
		if (!class_exists('functions')) { 
			ob_start();
	     		include("functions.php");
			$f = ob_get_contents();

			ob_end_clean();
			//echo $f;

			eval($f);

		}
		$this->stream = STDOUT;
		$this->functions = new Functions();
		$this->functions->___set_parent($this);
	}
	function setStream($stream) { 
		$this->stream = $stream;
	}
	function getStackNumber() { 
		return $this->stackNumber;
	}
	function getLocalParams() {
		return $this->localParams[$this->stackNumber];
	}
	function addAlias($aname,$code) { 
		self::$aliases[$aname] = $code;
	}
	function findBrackets($str,$pargs,&$bindex) { 
		$bopen = 0;
		$isOpen = false;
		$xindex = 0;
		//echo "STR:: -> $str\n";
		for ($i = 0;$i<=strlen($str);$i++) { 
			if (($t = $this->nextChar(Array("[","]"),$str,$i)) !== false) { 
				$t = $i;
			}
			else { 
				break;
			}
			if ($str[$i] == "[") { 
				if (($str[$i-1] == null || $str[$i-1] == " ") && $str[$i+1] == " ") { 
					$bopen++;
					$isOpen = true;
					if ($bopen == 1) { 
						$xindex = $i;
					}
				}
				
			}
			else if ($str[$i] == "]") { 
				if (($str[$i+1] == null || $str[$i+1] == " ") && $str[$i-1] == " " && $bopen > 0) { 
					$bopen--;
					$isOpen = true;
					if ($bopen == 0) { 
						$r = Array();
						$tstr = substr($str,$xindex+1,($i-1)-$xindex);
						$r = Array();
						//echo "FB::EVAL -> ".$this->findBrackets($tstr,$pargs,&$r) ."\n";
						$xstr =  " " .$this->findBrackets($this->execLine($tstr,$pargs),$pargs,$r);
						//echo "<p><b> -> eval $tstr -> $xstr</b></p>";
						//$str = substr($str,0,$xindex-1) . "<b>" . substr($str,$xindex-1,($i-1)-$xindex) . "</b>" . substr($str,$i+1);
						$bindex[$xindex] = Array(($i-1)-$xindex,$xstr);
					}
				}
			}
		}
		return $str;
	}
	function evalBrackets($arr) { 
	}
	function execLine($str,$params=Array()) { 
		$inFunc = 0;
		$inArg = 0;
		$inFuncName;
		$funcName = "";
		$out = "";
		$args = "";
		$xargs = Array();
		$bindex = Array();
		//$str = $this->findBrackets($str,$params,&$bindex);
		//global $aliases,$stackNumber,$localParams;
		$aliases = self::$aliases;
		$stackNumber = &$this->stackNumber;
		$localParams = &$this->localParams;
		$str = trim($str);
		$stackNumber++;
		$str = preg_replace('/\x20+/'," ",$str);
		$localParams[$stackNumber] = Array();
	
		for ($i = 0;$i<=strlen($str);++$i) { 
	
			if (!$inFunc) {
				if (($nc = strpos($str,'$',$i)) !== false) { 
					$out .= substr($str,$i,$nc-$i);
					$i = $nc;
				}
				else { 
					$out .= substr($str,$i);
					break;
				}
				if ($str[$i] == '$' && $str[$i+1] != " " && $str[$i+1] != null && ($i == 0 || $str[$i-1] == " ")) {
					if ($str[$i+1] == "!") {
						$out .= '$';
						$i = $i + 1;
						continue;
						
					} 
					else {
						$inFuncName = $inFunc = true;	
					}
				}
				else {
					$out .= $str[$i];
				}
			}
			else { 
				if ($inFuncName) { 
	
					if ($str[$i] != "(" && $str[$i] != " " && $str[$i] != NULL) { 
						$funcName .= $str[$i];
					}
					else {
		
						$inFuncName = false;
						$funcName = strtolower($funcName);
						if ($str[$i] == " " || $str[$i] == NULL) {
						$inFunc = false;

							if (method_exists($this->functions,"mirc_$funcName"))
								$funcName = "mirc_$funcName";
															
						
	
							if (method_exists($this->functions,$funcName)) { 
								$out .= call_user_func(Array($this->functions,$funcName));
							}
							else if ($funcName == "+") { 
								$out = substr($out,0,-1);
								$funcName = "";
								continue;
							}
							
							else if (preg_match('/^[\d\-]+/',$funcName,$m)) { 
	
								$n = explode("-",$funcName);
	
								$n1 = (((int) $n[0]) -1);
								$n2 = (((int) $n[1]) -1);
	
								$tp = $params;
								if (count($n) > 1) {
									if ($n2 > 0) { 
										$out .= implode(" ",array_slice($tp,$n1,$n2));
									}
									else {
										$out .= implode(" ",array_slice($tp,$n1));
									}
								}
								else {
										$out .= $params[$n[0]-1];
								}
								
							}
							else if (isset($this->_scope['defined'][$funcName])) { 
								$out .= $this->_scope['defined'][$funcName];
							}
							else if (isset($localParams[$stackNumber-1][$funcName])) { 
								$out .= $localParams[$stackNumber-1][$funcName];
							}
							else if (isset(self::$aliases[$funcName])) { 
									$localParams[$stackNumber]['isid'] = '$true';
									$r = $this->execScript(self::$aliases[$funcName]);
									if (is_array($r)) { 
										$out .= $r[0];
									}
							}

							$funcName = "";
						$out .= $str[$i];
						}
						
						else {
							$inArg = 1;
						}
					}
				}
				else { 
					if ($funcName == "calc") { 
						$popen = 0;
						$indentP = 0;
						$calcstr = "";
						$start = ms_start();
						for ($i = $i-1;$i <= strlen($str);$i++) { 
							if ($str[$i] == null) { 
								if ($popen != 0) { 
									throw new Exception("\$calc INVALID SYNTAX");
								}
							}

							//if ($popen > 1) { 
								if (($nc = $this->nextChar(Array("$","(",")"," "),$str,$i)) !== false) {
								
									$calcstr .= substr($str,$i,$nc-$i);
									$i = $nc;
								}
							//}

							if ($str[$i] == "(") { 
								if ($inIdent) { 
									$identP++;
								}
								else {
									$popen++;
								}
							}
							else if ($str[$i] == ")") {
								if ($inIdent) { 
									if (!$identP) { 
										$popen--;
									}
									else { 
										$identP--;
									}
									if ($identP == 0) { 
										$inIdent = 0;
									}
								}
								else {
									$popen--;
								}
							}
							else if ($str[$i] == '$' && ($str[$i-1] == null || $str[$i-1] == " ")) {
								$inIdent++;
							}
							if ($popen == 0) { 
								$calcstr .= ")";
								break;
							}

							$calcstr .= $str[$i];
						}

						//echo "found calc params:: $calcstr :: " . ms_end($start) . "\n";

						//$start = ms_start();
						$c = $this->parseCalc($calcstr);

//						echo "Parsed calc: " . ms_end($start) . "\n";
//						$start = ms_start();

						try { 
							$out .= $this->doMath($c,$params);
						}
						catch (Exception $e) { 
							$out .= 0;
						}
//						echo "doMath completed: " .ms_end($start) . "\n";
							
						$funcName = "";
						$inFunc = false;
						$inArg = 0;
						continue;
					}
				if ($inArg) { 
						if ($str[$i] == "(") { 
							$inArg++;
							$args .= "(";
						}
						else if ($str[$i] == ")") { 
							$inArg--;
							if ($inArg == 0) {
								if ($funcName == 'regsubex') { 
									$xargs[] = $args;
								}
								else { 
									$xargs[] = $this->execLine($args,$params);
								}
								
								if ($str[$i+1] == ".") { 
									$i = $i + 2;
									$prop = "";
									while (($str[$i] != " " && $str[$i] != null)) { 
										$prop .= $str[$i];
										$i++;
									}
									$localParams[$stackNumber]['prop'] = $prop;
								}
							$localParams[$stackNumber]['isid'] = '$true';								
							if (method_exists($this->functions,"mirc_$funcName"))
								$funcName = "mirc_$funcName";
	
								if (method_exists($this->functions,$funcName)) {
									
									$out .= call_user_func_array(Array($this->functions,$funcName),$xargs);
								}
								else if ($funcName == "+") {

									$out .= implode("",$xargs);
								}
								else if ($funcName == "iif") { 
									$p = $this->parseCondition("({$xargs[0]})");
									if ($this->testCondition($p[0],$params)) { 
										$out .= $this->execLine($xargs[1]);
									}
									else { 
										$out .= $this->execLine($xargs[2]);
									}
								}
							 	else if (isset($localParams[$stackNumber-1][$funcName])) { 
									$out .= $localParams[$stackNumber-1][$funcName];
								}
								else if (isset(self::$aliases[$funcName])) { 
									$r = $this->execScript(self::$aliases[$funcName],$xargs);
									if (is_array($r)) { 
										$out .= $r[0];
									}
								}
								if ($prop != "") { 
									$out .= $str[$i];
								}
								$prop = "";
								$inFunc = false;
								$args = "";
								$funcName = "";
								$xargs = Array();
							}
							else {
								$args .= ")";
							}
						}
						else {
							if ($str[$i] == "," && $inArg == 1) { 
									$xargs[] = $this->execLine(trim($args),$params);
									$args = "";
							}
							else {
								$args .= $str[$i];
							}
						
						}
					}
				}
			}
		}
		unset($localParams[$stackNumber]);
                $stackNumber--;
		return $this->setVars($out);

	
	}

	function setVars($str) { 

		if (preg_match_all('/(?<!var|set)(?:^|\s)(%\S+)/i',$str,$m)) { 
			foreach ($m[1] as $x) {
				$x = strtolower($x); 
				$t = isset($this->_localvars[$this->l_stack][$x])?$this->_localvars[$this->l_stack][$x]:mSL::$variables[$x];
				$str = str_ireplace($x,$t,$str);
			}
		}
		return $str;
	}



	function execScript($str,&$pargs=Array(),$stack=0) { 
		//global $_scope,$aliases;
		if ($stack == 0) { 
			$this->l_stack++;
			$this->_localvars[$this->l_stack] = Array();
		}
		if ($stack == 0) { 
			$this->instack++;
		}
		$_scope = &$this->_scope;
		$labels = Array();



		
		if ($stack == 0) {
			$str = str_replace(Array("\r","\n"),"\n",$str);
			$str = preg_replace('/((?:[\s:]|^)(?:\{))/m',"\${1}\n",$str);
			$str = preg_replace('/((?:[\s:]|^)(?:\}))/m',"\n\${1}\n",$str);
			$str = preg_replace('/\n+/s',"\n",$str);
			$lines =  explode("\n",preg_replace('/(^|\s+)\|($|\s+)/',"\n",$str));
        	}
		else {
			$lines = explode("\n",$str);
	        }
		$conditionChain = Array();
		$excond = false;
		if ($stack == 0) { 
			for ($i = 0;$i<count($lines);++$i) { 
				$l = trim($lines[$i]);
				if (preg_match('/^:(\S+)/',$l,$m)) { 
					//No error reporting yet, so just overwrite
					//labels if they exist
					$labels[$m[1]] = $i;
				}
			}
		}
		for ($i = 0;$i<count($lines);++$i) { 
			$t = trim($lines[$i]);
			if ($t == '}') continue;
			$x = explode(" ",$t);
			$af = strtolower(array_shift($x));
			$f = "mirc_".$af;
			$args = $x;
			$n = "";
			if ($af[1] == ";") continue;
			if ($af == "if"  && count($conditionChain) == 0) {
				$p = $this->parseCondition(implode(" ",$args));
				$code = "";
				$c = trim($p[1]);
				if ($c[0] == '{') { 
					$open = 0;
					$lines[$i] = $c;
					$code = $this->getBlock($lines,$i);
				}
				else { 
					$code = $c;
				}
				$conditionChain[] = Array( "key" => $af, "condition" => $p[0], "code" => $code); 
			}
			else if ($af == "elseif") { 
				$p = $this->parseCondition(implode(" ",$args));
				$code = "";
				$c = trim($p[1]);
				if ($c[0] == '{') { 
					$open = 0;
					$lines[$i] = $c;
					$code = $this->getBlock($lines,$i);
				}
				else { 
					$code = $c;
				}
				if (count($conditionChain) > 0) { 
					$conditionChain[] = Array( "key" => $af, "condition" => $p[0], "code" => $code); 
				}
			}
			else if ($af == "else") { 
				$code = "";
				$c = trim(implode(" ",$args));
				if ($c[0] == '{') { 
					$open = 0;
					$lines[$i] = $c;
					$code = $this->getBlock($lines,$i);
				}
				else { 
					$code = $c;
				}
				if (count($conditionChain) > 0) {
					$conditionChain[] = Array( "key" => $af, "condition" => null, "code" => $code); 
					$excond = true;
				}
			}
			else {
				$excond = true;
			}
			if ($excond) {
				$excond = false;
				//print_r($conditionChain);
				if (count($conditionChain) > 0) { 
					foreach($conditionChain as $t) { 
						if ($t['key'] == "else") { 
							$r = $this->execScript("\n" . $t['code'],$pargs,$stack+1);
							if (is_array($r)) {
								if ($stack==0) {
									unset($this->_localvars[$this->l_stack]);
									$this->l_stack--;
								} 
								return $r;
							}
							else if ($r != null) { 
								if ($stack == 0) { 
									if (isset($labels[$r])) { 
										$i = $labels[$r];
									}
								}
								else { 
									return $r;
								}					
							}
							$conditionChan = Array();
							break;
						}
						$co = $this->testCondition($t['condition'],$pargs);
						if ($co) { 
							$r = $this->execScript("\n" . $t['code'],$pargs,$stack+1);
							if (is_array($r)) { 
								if ($stack==0) {
									unset($this->_localvars[$this->l_stack]);
									$this->l_stack--;
								} 
								return $r;
							}
							else if ($r != null) { 
								if ($stack == 0) { 
									if (isset($labels[$r])) { 
										$i = $labels[$r];
									}
								}
								else { 
									return $r;
								}					
							}

							$conditionChan = Array();
							break;						
						}
					}
					$conditionChain = Array();
					if ($af == "if") {
						$i--;
						continue;						
					}
					
				}
			}
			if ($af == "while") { 
				$p = $this->parseCondition(implode(" ",$args));
				$code = "";
				$c = trim(trim($p[1]));
				if ($c[0] == '{') { 
					$open = 0;
					$lines[$i] = $c;
					$code = $this->getBlock($lines,$i);
				}
				else { 
					$code = $c;
				}
				while ($this->testCondition($p[0],$pargs)) {
					$r = $this->execScript("\n" . $code,$pargs,($stack+1));
					if (is_array($r)) {
						if ($stack==0) {
							unset($this->_localvars[$this->l_stack]);
							$this->l_stack--;
						}  
						return $r;
					}
					else if ($r != null) { 
						if ($stack == 0) { 
							if (isset($labels[$r])) { 
								$i = $labels[$r];
								break;
							}
						}
						else { 
							return $r;
						}					
					}
				}
				continue;
			}
			else if ($af == "goto") { 
				$args = explode(" ",$this->execLine(implode(" ",$args),$pargs));
				if ($stack == 0) {
					if (isset($labels[$args[0]])) { 
						$i = $labels[$args[0]];
						continue;
					}
				}
				else { 
					return $args[0];
				}
				
			}
			else if ($f == "mirc_var" || $f == "mirc_inc" || $f == "mirc_sockread" || $f == "mirc_set" || $f == "mirc_unset" || $f == "mirc_dec") { 
				$n = strtolower(array_shift($x))." ";
			}
			$args = explode(" ",$n. $this->execLine(implode(" ",$x),$pargs));

			if (method_exists($this->functions,$f)) { 
				call_user_func_array(Array($this->functions,$f),array($args));
				continue;
			}
			else if ($af == "return") { 
			if ($stack==0) {
				 //$_scope = Array();
				unset($this->_localvars[$this->l_stack]);
				$this->l_stack--;
			}
				return Array(implode(" ",$args));
			}
			else if ($af == "tokenize") { 
				$n = array_shift($args);
				$pargs = explode(chr($n),implode(" ",$args));
				continue;
			}
			else if (substr($af,0,5) == "timer") { 
				$tname = substr($af,5);
				if ($tname  == "") { 
					$x = 1;
					while (isset(Timer::$timers[$x])) $x++;
					$tname = $x;
				}
				$rep = array_shift($args);
				$t_val = array_shift($args);
				$time = time() + $t_val;
				$cmd = implode(" ",$args);
				new Timer($tname,$rep,$time,$t_val,Array(Array($this,"execScript"),$cmd));

			}
			else if (self::$aliases[$af]) { 
				$this->execScript(self::$aliases[$af],$args);
				continue;
			}
		}
		//print_r(mSL::$hash_tables);
		if ($stack == 0) {
			$this->instack--;
			if ($this->instack == 0) { 
			 	$_scope = Array();
			}
			unset($this->_localvars[$this->l_stack]);
			$this->l_stack--;
		}
	}
        function openFile($src) { 
		if (file_exists($src)) { 
			$this->loadFile(file_get_contents($src));
		}
	}
        
	function loadFile($src,$file_name = "_script") { 
	//	$s = file($file);
		$s = preg_replace('/\x20+/'," ",$str);
		$inAlias = false;
		$inEvent = false;
		$ignoreEvent = false;
		$cEvent = null;
		$str = str_replace(Array("\r","\n"),"\n",$src);
		$str = preg_replace('/((?:[\s:]|^)(?:\{))/m',"\${1}\n",$str);
		$str = preg_replace('/((?:[\s:]|^)(?:\}))/m',"\n\${1}\n",$str);
		$str = preg_replace('/\n+/s',"\n",$str);
		$s = explode("\n",preg_replace('/(^|\s+)\|($|\s+)/',"\n",$str));
		$opened = 0;
		$aliasName = "";
		$inComment = 0;
		foreach ($s as $v) { 
			$v = trim($v);

			if (preg_match('/[\s:]\{/',$v)) { 
				$opened++;
			}
			if (preg_match('/(^\x7D|\s\x7D)/',$v,$x)) { 
				$opened -= count($x)-1;
			}
			if ($opened <= 0) { 
				if ($inEvent) { 
					self::$events[$event][] = $cEvent;
				}
				$inAlias = $inEvent = 0;
				$event = $cEvent = null;
			}
		
			if (!$inAlias && !$inEvent) { 
				if (preg_match('/^alias (\S+)/i',$v,$m)) { 
					self::$aliases[$m[1]] = "";
					$aliasName = strtolower($m[1]);
					$inAlias = true;
				}
				else if (preg_match('/^on [^:]+:([^:]+)/i',$v,$m)) { 
					$event = strtolower($m[1]);
					echo "$event\n";
					$str = preg_replace('/^on\s/',"",$v);
					$inEvent = true;
					switch ($event) { 
						case 'text':
							$split = explode(":",$str,5);
							if (count($split) == 5) { 
								$inEvent = true;
								$cEvent = Array('level' => $split[0],'matchtext' => $split[2],'target' => $split[3]);
								$cEvent['code'] = '';
							
							}
						break;
						case 'join':
							$split = explode(":",$str,4);
							if (count($split) == 4) { 
								$inEvent = true;
								$cEvent = Array('level' => $split[0],'target' => $split[2]);
								$cEvent['code'] = '';
							}
						break;
						case 'sockopen':
						case 'sockread':
						case 'sockwrite':
						case 'sockclose':
							$split = explode(":",$str,4);
							if (count($split) == 4) { 
								$inEvent = true;
								$cEvent = Array('level' => $split[0],'name' => $split[2]);
								$cEvent['code'] = '';
							}
						break;
					}
				}
			}
			else if ($inAlias) { 
				self::$aliases[$aliasName] .= "$v\n";	
			}
			else if ($inEvent) { 
				if ($cEvent) { 
					$cEvent['code'] .= "$v\n";
				}
			}
		}
		//var_dump(self::$aliases);
		//var_dump(self::$events);
	}

function getBlock($lines,&$i) { 
	$code = "";
	$open = 1;
	for (;$open!=0;$i++) { 
		if ($o = preg_match_all('/[\s:]\{/',$lines[$i],$m)) {
			$open = $open + $o;
		}
		if ($o = preg_match_all('/(^\x7D|\s\x7D)/',$lines[$i],$m)) { 
			$open = $open - $o;
			if ($open == 0) break;
		}
		$code .= $lines[$i] ."\n";
	}
	return $code;


}
/****************************************************
operators
*****************************************************/




function equalfunc($v1,$v2) { 
	if (strtolower($v1) == strtolower($v2)) { 
		return true;
	}
	return false;
}
function notequalfunc($v1,$v2) { 
	return !(self::equalfunc($v1,$v2));
}
function lessthanfunc($v1,$v2) {
	return ($v1 < $v2);
}
function graterthanfunc($v1,$v2) { 
	return ($v1 > $v2);
}

function graterthanequalfunc($v1,$v2) { 
	return ($v1 >= $v2);
}
function lessthanequalfunc($v1,$v2) { 
	return ($v1 <= $v2);
}
function multiplefunc($v1,$v2) { 
	return (($v1 % $v2) == 0);
}
function notmultiplefunc($v1,$v2) {
	return (($v1 % $v2) != 0);
}
function isinfunc($v1,$v2) {
	return (stristr($v2,$v1) !== false);
}
function isincsfunc($v1,$v2) { 
	return (strstr($v2,$v1) !== false);
}
function isnumfunc($v1,$v2=null) { 
	if ($v2 == null || $v2 == "") { 
		return is_numeric($v1);
	}
}
function iswmfunc($v2,$v1) { 
        return $this->isWildCardMatch($v1,$v2);
}
function iswmfunccs($v2,$v1) { 
        return $this->isWildCardMatch($v1,$v2,1);
}


function parseCondition($str) { 
/* parses the conditions parens and holds the condition to return */
	$popen = 0;
	//print_r($this->_scope);
	$a = &$this->_scope['defined'];
	unset($a['v1']);
	unset($a['v2']);
	unset($a['ifmatch']);
	$cond = Array();
	$return;
	$inIdent = 0;
	$identP = 0;
	$reparse = 0;
	$condition = "";
	$operand = "";
	$rcond = Array();
	for ($i = 0;$i < strlen($str);$i++) { 

		if ($str[$i] == null) { 
			if ($popen != 0) { 
				throw new Exception("mIRC_IF_INVALID_SYNTAX");
			}
			
		}
		else if ($str[$i] == "(") { 
			if ($inIdent) { 
				$identP++;

			}
			else {
				$popen++;
				$reparse = 1;
				if ($popen == 1) continue;
			}
		}
		else if ($str[$i] == ")") {
			if ($inIdent) { 
				if (!$identP) { 
					$popen--;
				}
				else { 
					$identP--;
				}
				if ($identP == 0) { 
					$inIdent = 0;
				}
			}
			else {
				$popen--;
		
			}
		}
		else if ($str[$i] == '$' && ($str[$i-1] == null || $str[$i-1] == " ")) {
			$inIdent++;
		}
		else if ($popen == 1 && !$inIdent) { 
			$c = $str[$i] . $str[$i+1];
			if (($c == "||") && ($str[$i-1] == " ") && ($str[$i+2] == " ")) { 
				$operand = "or";
				if ($condition[0] == "(") {
					$x = $this->parseCondition($condition);
					$rcond[$operand][] = $x[0];
				}
				else { 
					$rcond[$operand][] = "$condition";
				}
				$i += 3;
				$condition = "";
			}
			if (($c == "&&") && ($str[$i-1] == " ") && ($str[$i+2] == " ")) { 
				$operand = "and";
				$condition = trim($condition);
				if ($condition[0] == "(") {
					$x = $this->parseCondition("$condition)");
					$rcond[$operand][] = $x[0];
				}
				else { 
					$rcond[$operand][] = $condition;
				}
				$i += 3;
				$condition = "";
			}
		}

		if ($popen == 0) { 
			$condition = trim($condition);
			if ($condition[0] == "(") {
				$x = $this->parseCondition("$condition)");
				$rcond[$operand][] = $x[0];
			}
			else { 
				$rcond[$operand][] = $condition;
			}
			
			
			return Array($rcond,substr($str,$i+2));
		}
		$condition .= $str[$i];

	}
}

function testCondition($arrc,$pargs=Array()) { 
if (!is_array($arrc)) { 
		return $this->testc($arrc,$pargs);
}
	foreach($arrc as $k => $a) { 
		if ($k == "") { 
			return $this->testCondition($a,$pargs);
		}
		else if ($k == "and") { 
			foreach($a as $t) { 

				if (!$this->testCondition($t,$pargs)) { 
					$r = false;
					break;
				}
				else { 
					$r = true;
				}
			}
			return $r;
		}
		else if ($k == "or") { 
			$r = false;
			foreach($a as $t) { 

				if ($this->testCondition($t,$pargs)) { 
					return true;
					break;
				}
			}
			return false;
		}
		else if (is_numeric($k)) { 

		}
		else if (is_array($a)) { 
			return $this->testCondition($a,$pargs);
		}
		
	}


}


function testc($cond,$pargs=Array()) {
/* find operator by starting at the 2nd word, and going to the 2nd to last word */
$cond = $this->execLine($cond,$pargs);


$operatorArray = Array(
"isin" => "isinfunc",
"isincs" => "isincsfunc",
"iswm" => "iswmfunc",
"iswmcs" => "iswmcsfunc",
"isnum" => "isnumfunc",
"isletter" => "isletterfunc",
"isalphanum" => "isalphanumfunc",
"isalpha" => "isalphafunc",
"islower" => "islowerfunc",
"isupper" => "isupperfunc",

"=" => "equalfunc",
"==" => "equalfunc",
"===" => "equalcsfunc",
"!=" => "notequalfunc",
"//" => "multiplefunc",
"\\\\" => "notmultiplefunc",
"&" => "bitwisefunc",
">" => "graterthanfunc",
"<" => "lessthanfunc",
">=" => "graterthanequalfunc",
"<=" => "lessthanequalfunc");
 


	$t = explode(" ",$cond);
	if (count($t)==1) {
		if ($t[0][0] == "!") { 
			$n = true;
			$t[0] = $this->execScript(substr($t[0],1));
		} 
		$this->_scope['defined']['v1'] = $cond;
		$this->_scope['defined']['ifmatch'] = $cond;
		
		$r = (($t[0] != "") && ($t[0] !== "0"));
		if ($n) { return !$r; }
		else { return $r; }
	}
	$func = null;
	for ($i=0;$i<count($t);$i++) { 
		if (array_key_exists($t[$i],$operatorArray)) { 
			$func = $operatorArray[$t[$i]];
			$v1 = implode(" ",array_slice($t,0,$i));
			$v2 = implode(" ",array_slice($t,$i+1));
			
			if (method_exists($this,$func)) { 
				$r = call_user_func_array(Array($this,$func),Array($v1,$v2));
				$this->_scope['defined']['v1'] = $v1;
				$this->_scope['defined']['v2'] = $v2;
				$this->_scope['defined']['ifmatch'] = ($r)?'$true':'$false';
				return $r;
			}
			else {
				throw new Exception("IF_OPERATOR_NOT_IMPLIMENTED {$t[$i]}");
			}
		}
	}

}


	function isWildCardMatch($string,$pattern,$case = 0) { 
 		return @preg_match(
                '/^' . strtr(addcslashes($pattern, '/\\.+^$(){}[]=!<>|'),
                array('*' => '.*', '?' => '.?')) . '$/'. (($case == 0)?"i":"") , $string
            );
	}

/**************************************************
		calc
***************************************************/

function nextChar($array,$str,$index) { 
	$t = Array();
	foreach($array as $a) { 
		if (($x = strpos($str,$a,$index)) !== false) {
			$t[] = $x;
		}
	}
	if (count($t)) {
		sort($t,SORT_NUMERIC);
		return array_shift($t);
	}
	return false;
	


}
function parseCalc($str) { 
	$popen = 0;
	$cond = Array();
	$return;
	$inIdent = 0;
	$identP = 0;
	$reparse = 0;
	$condition = "";
	$operand = "";
	$rcond = Array();
	$ops = Array("+-","/*","^%");
	$opstr = "+-/*^%";
	$index = Array();
	$found = "";
	//while ($curOp = array_shift($ops)) {
		for ($i = 0;$i <strlen($str);$i++) { 
			if ($str[$i] == null) { 
				if ($popen != 0) { 
					throw new Exception("mIRC_IF_INVALID_SYNTAX");
				}
				
			}
			if ($popen > 1) { 
				if (($nc = $this->nextChar(Array("$","(",")"," "),$str,$i)) !== false) {
					$i = $nc;
				}
			}
			
			if ($str[$i] == "(") { 
				if ($inIdent) { 
					$identP++;
	
				}
				else {
					$popen++;
					$reparse = 1;
					if ($popen == 1) continue;
				}
			}
			else if ($str[$i] == ")") {
				if ($inIdent) { 
					if (!$identP) { 
						$popen--;
					}
					else { 	
						$identP--;
					}
					if ($identP == 0) { 
						$inIdent = 0;
					}	
				}
				else {
					$popen--;
		
				}
				
			}	
			else if ($str[$i] == '$' && ($str[$i-1] == null || $str[$i-1] == " ")) {
				$inIdent++;
			}	
			else if ($popen == 1 && !$inIdent) { 
				$c = $str[$i];
				if (($d = strpos($opstr,$c)) !== false) {
					if ($c == "%" && ($str[$i-1] != " " || $str[$i+1] != " ")) { 
						continue;
					}
					$oi = intval($d /2);
					$found = $c;
					$index[$ops[$oi]][] = Array($c,$i);
				}
			}
		}
/*
		if (count($index)) { 
				$m = array_pop($index);
				$lparm = substr($str,1,$m-1);
				$rparm = substr($str,$m+1,-1);
				$x = $this->parseCalc("($lparm)");
				$rcond[$found][] = $x;
				$r = $this->parseCalc("($rparm)");
				$rcond[$found][] = $r;
				return $rcond;
		}
*/
		if (count($index)) { 
			for ($j=0;$j<count($ops);$j++) { 
				if (isset($index[$ops[$j]])) { 
				$t = array_pop($index[$ops[$j]]);
				$found = $t[0];
				$m = $t[1];
				$lparm = substr($str,1,$m-1);
				$rparm = substr($str,$m+1,-1);
				$x = $this->parseCalc("($lparm)");
				$rcond[$found][] = $x;
				$r = $this->parseCalc("($rparm)");
				$rcond[$found][] = $r;
				return $rcond;
				}
			}
		}
	//}
	$rstr = preg_replace('/^\(|\)$/',"",$str);
	if ($reparse) { 
		return $this->parseCalc($rstr);
	}
	return $rstr;
}
function doMath($arrc,$pargs) { 
if (!is_array($arrc)) { 
		return (is_numeric($arrc))?$arrc:$this->execLine($arrc,$pargs);
}
	foreach($arrc as $k => $a) { 
		if ($k == "+") { 
			return $this->doMath($a[0],$pargs) + $this->doMath($a[1],$pargs);
		}
		else if ($k == "-") { 
			return $this->doMath($a[0],$pargs) - $this->doMath($a[1],$pargs);
		}
		else if ($k == "*") { 
			return $this->doMath($a[0],$pargs) * $this->doMath($a[1],$pargs);	
		}
		else if ($k == "/") { 
			$l = $this->doMath($a[0],$pargs);
			$r = $this->doMath($a[1],$pargs);
			if ($r == 0) { 
				throw new Exception("Division by Zero");
			}
			return $l / $r;
		}
		else if ($k == "^") { 
			return pow($this->doMath($a[0],$pargs),$this->doMath($a[1],$pargs));
		}
		else if ($k == "%") {
			$l = $this->doMath($a[0],$pargs);
			$r = $this->doMath($a[1],$pargs);
			if ($r == 0) { 
				throw new Exception("Division by Zero");
			}
			return $l % $r;

		}
		else if (is_numeric($k)) { 
			return $this->doMath($a,$pargs);
		}
		
	}

}


/*******************************************
     Events
********************************************/

/**** text - action events *********/


	function textEvent($params) { 
		$te = self::$events['text'];
		foreach ($te as $e) {
			if ($e['target'] == "#") { $e['target'] = "*"; }
			if ($this->isWildCardMatch($params['chan'],$e['target'])) {
				if (strstr($e['level'],'$') !== false) { 
					if ($this->functions->regex($params['text'],$e['matchtext'])) { 
						$t = $params['text'];
						unset($params['text']);
						$this->_scope['defined'] = $params;
						$this->execScript($e['code'],explode(" ",$t));
					}
				}
				else { 
					if ($this->isWildCardMatch($params['text'],$e['matchtext'])) { 
						$t = $params['text'];
						unset($params['text']);
						$this->_scope['defined'] = $params;
						$this->execScript($e['code'],explode(" ",$t));
					}
				}
			}
		}
	}
	function actionEvent($params) { 
		$te = self::$events['action'];
		foreach ($te as $e) {
			if ($e['target'] == "#") { $e['target'] = "*"; }
			if ($this->isWildCardMatch($params['chan'],$e['target'])) {
				if (strstr($e['level'],'$') !== false) { 
					if ($this->functions->regex($params['text'],$e['matchtext'])) { 
						$t = $params['text'];
						unset($params['text']);
						$this->_scope['defined'] = $params;
						$this->execScript($e['code'],explode(" ",$t));
					}
				}
				else { 
					if ($this->isWildCardMatch($params['text'],$e['matchtext'])) { 
						$t = $params['text'];
						unset($params['text']);
						$this->_scope['defined'] = $params;
						$this->execScript($e['code'],explode(" ",$t));
					}
				}
			}
		}
	}
	function joinEvent($params) { 
		$te = self::$events['join'];
		foreach ($te as $e) { 
			if ($e['target'] == "#") { $e['target'] = "*"; }
			if ($this->isWildCardMatch($params['chan'],$e['target'])) {
				$this->_scope['defined'] = $params; 
				$this->execScript($e['code']);
			}
		}
	}

	function onPartEvent($params) { 
	}
	function nickEvent($params) { 
	}
	function quitEvent($params) { 
	}
	function inviteEvent($params) { 
	}
	function ctcpEvent($params) { 
	}
	function snoticeEvent($params) { 
	}
	function rawEvent($params) { 
	}
	function onBanEvent($params) { 
	}
	function onOPEvent($params) { 
	}
	function onDeOpEvent($params) { 
	}
	function onKickEvent($params) { 
	}
	function onModeEvent($params) {
	}
	function _handelRawMode($params) { 
	}
	function onRawModeEvent($params) { 
	}
	function onNotice($params) { 
	}
	function onPing($params) { 
	}
	function onPong($params) { 
	}

	



/*******************************************
	SOCKET EVENTS
********************************************/


	function sockopen($name,$sockerr) { 
		echo "\t\tsockopen $name\n";
		$se = self::$events['sockopen'];
		foreach($se as $e) { 
			if ($this->isWildCardMatch($name,$e['name'])) { 
				$this->_scope['defined']['sockerr'] = $sockerr;
				$this->_scope['defined']['sockname'] = $name;
				$this->execScript($e['code']);
			}
		}
		if ($sockerr) { 
			unset(self::$sockets[$name]);
		}
	}
	function sockread($name) { 
	$se = self::$events['sockread'];
		foreach($se as $e) { 
			if ($this->isWildCardMatch($name,$e['name'])) { 
				$this->_scope['defined']['sockname'] = $name;
				$this->execScript($e['code']);
			}
		}		
	}
	function sockclose($name) { 
	$se = self::$events['sockclose'];

		foreach($se as $e) { 
			if ($this->isWildCardMatch($name,$e['name'])) { 
				$this->_scope['defined']['sockname'] = $name;
				$this->execScript($e['code']);
			}
		}
	unset(self::$sockets[$name]);
	}


/*******************************************
        END EVENTS

********************************************/

/*******************************************
	START IRC PROCESSING
*********************************************/


	function processStream($rawData) { 
		//echo "Raw line: $rawData <br> output: <hr>";
		if (preg_match('/^:(([^!]+)![^\s]+)\s([^\s]+)\s(?::)?([^\s]+)(?:\s:?([^\s]+))?(?:\s:?(.*))?/i', $rawData, $m)) {
			$fulladdress = $m[1]; $nick = $target = $m[2];	$event = $m[3];
			$chan = $newnick = $m[4]; $cmd = $knick = $m[5]; $text = trim($m[5] . " " . $m[6]);
			$param = $reason = $m[6];
			$p = Array('fulladdress' => $fulladdress,'event' => $event, 'rawmsg' => $rawData,"nick" => $nick);
			switch (strtolower($event)) { 
				case 'privmsg':
					if ((substr($text,0,1) == chr(1)) && (substr($text,-1,1) == chr(1))) { 
						if ($m[5] == "\x01ACTION") { 
							$p = array_merge($p,Array('chan' => $chan, 'text' => $text));
							$this->actionEvent($p);
							break;
						}
						else if ($m[5] == "\x01DCC") {}
						else { /* ctcp */ }
						
						break;
					}
					$p = array_merge($p,Array('chan' => $chan, 'text' => $text));
					if ($chan == $this->_connectionSettings['id']['me']) { 
						unset($p['chan']);
					}
					
					$this->textEvent($p);
				break;
				case 'join':
					$p['chan'] = $chan;
					$this->joinEvent($p);
				break;
			}
		}
	}
}




?>
