
	function mirc_sockopen($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		if (count($args) == 3) { 
			list($sockname,$host,$port) = $args;
				$sock = new Socket($sockname,$host,$port,$this->parent,stristr($switch,"e") !== false);
				mSL::$sockets[$sockname] = $sock;
				$sock->connect();
		}
	}
	function mirc_sockread($args) { 
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		$sockname = $this->parent->_scope['defined']['sockname'];
		if ($sockname) {
			$read = rtrim(mSL::$sockets[$sockname]->sockread(),"\r\n");
			$this->parent->_scope['defined']['sockbr'] = strlen($read);
			if ($args[0][0] == "&") { 
				$this->mirc_bset("-t 1 " . $args[0] . " " .$read);
			}
			else {
				$this->mirc_var(explode(" ",$args[0] . " $read"));
			}
		}
	}
	function mirc_sockwrite($args) {
		if ($args[0][0] == "-") { 
			$switch = array_shift($args);
		}
		$sockname = array_shift($args);
		$data = implode(" ",$args);
		if (stristr($switch,'n') !== false) { 
			$data .= "\r\n";
		}
		echo "\n Writing Data to socket: $sockname \n\t----> $data";
		if ($sock = mSL::$sockets[$sockname]) {
			$sock->sockwrite($data);
		}
	}
	function mirc_sockclose($args) { 
		foreach (mSL::$sockets as $sockname => $socket) { 
			if ($this->parent->isWildCardMatch($sockname,$arg[0])) { 
				$socket->sockclose();
			}
		}
	}
	function sock($name) { 
		if (!isset(mSL::$sockets[$name])) { 
			return "";
		}
		else { 
			$localParams = $this->parent->getLocalParams();
			$p = $localParams['prop'];
	                if (!$p) { 
				return $name;
			}
		}
	}
