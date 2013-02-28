
<?php
Class Timer { 
	public static $timers = Array();
	public static $ltimer;
	public $cmd;
	public $repTo;
	public $rep;
	public $time;
	public $name;
	
	function __construct($timerName,$rep_to,$time_to,$t_val,$command) {
		$this->name= $timerName;
		$this->repTo = $rep_to;
		$this->time = $time_to;
		$this->cmd = $command;
		$this->rep = 0;
		$this->time_val = $t_val;
		Timer::$timers[$this->name] = $this;
	}
	function tick() { 
		if ($this->time <= time()) { 
			
			call_user_func_array($this->cmd[0],Array($this->cmd[1]));
			$this->time = $this->time_val + time();
			$this->rep++;
			Timer::$ltimer = $this->name;
			if ($this->repTo != 0) { 
				if ($this->repTo >= $this->rep) { 
					unset(Timer::$timers[$this->name]);
				}
			}
		}
	}
}
?>
