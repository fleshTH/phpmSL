
	function mirc_msg($params) { 
		if (count($params) < 2) { 
			return;
		}
		$c = array_shift($params);
		$t = implode(" ",$params);
		fputs($this->parent->stream,sprintf("privmsg %s :%s\r\n",$c,$t));
	}
	function mirc_notice($params) { 
		if (count($params) < 2) { 
			return;
		}
		$c = array_shift($params);
		$t = implode(" ",$params);
		fputs($this->parent->stream,sprintf("NOTICE %s :%s\r\n",$c,$t));

	}
	function mirc_join($params) { 
		if (count($params) < 1) { 
			return;
		}
		$c = array_shift($params);
		$k = implode(" ",$params);
		fputs($this->parent->stream,sprintf("JOIN %s :%s\r\n",$c,$k));
	}
	function mirc_part($params) { 
		if (count($params) < 1) { 
			return;
		}
		$c = array_shift($params);
		$r = implode(" ",$params);
		fputs($this->parent->stream,sprintf("PART %s :%s\r\n",$c,$r));

	}
	function mirc_invite($params) { 
	}
	function mirc_quit($params) {
		
	}

