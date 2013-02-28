
<?
Class socket { 
	public static $sockets = Array();
	public $prop = Array();
	public $socket;
	private $parent;
	public $host;
	public $port;
	private $buffer = Array();
	function __construct($name,$host,$port,$p,$ssl = false) {
		$this->name = $name;
		$this->host = $host;
		$this->port = $port;
		$this->prop = Array("name" => $name,
		"addr" => "",
		"sent" => 0,
		"rcvd" => 0,
		"sq" => 0,
		"rq" => 0,
		"mark" => "",
		"type" => "TCP",
		"saddr" => "",
		"sport" => "",
		"to" => 0,
		"wserr" => 0,
		"wsmsg" => 0,
		"ssl" => false,
		"pause" => false);
		$this->parent = $p;
		echo "opening $name for $host:$port";
		

	}
	function connect() { 
		
		$this->socket = fsockopen($this->host,$this->port, $errno, $errstr, 2);
		if (!$this->socket) {
			$this->prop['wserr'] = $errno;
			$this->prop['wsmsg'] = $errstr;
			echo "\n***********socket error $errstr *************\n";
		}
		global $connections;
		array_push($connections,$this);
		$this->parent->sockopen($this->name,$errno);
	}
	function readFromSocket() { 
		
		$r = fgets($this->socket);
		echo "reading {$this->name} buffer ". count($this->buffer) ."\n $r\n";
		$this->buffer[] = trim($r);
		$this->parent->sockread($this->name);

	}
	function sockread() { 
		if (count($this->buffer)) { 
			return array_shift($this->buffer);
		}
		return "";
	}
	function socketClosed() { 
		global $connections;
		unset($connection[$this]);
		$this->parent->sockclose($this->name);
	}
	function sockclose() { 
		global $connections;
		unset($connection[$this]);
		fsockclose($this->socket);
	}
	function sockwrite($data) { 
		fputs($this->socket,$data);
	}
	function sockmark($data) { 
		$this->mark = $data;
	}
}
?>
