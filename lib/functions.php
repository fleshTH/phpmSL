
<?
if (isset($_GET['num'])) { 
ob_start();
}
?>
Class Functions {
	private $parent;
	private $regex_names = Array();
	function ___set_parent($obj) { 
		$this->parent = $obj;
	}
<?
	include_once('time_date_identifiers.php');
	include_once('text_identifiers.php');
	include_once('token_identifiers.php');
	include_once('regular_expressions.php');
	include_once('number_identifiers.php');
	include_once('irc.php');
	include_once('socket_functions.php');
	include_once('hash_tables.php');
	include_once('bvar_functions.php');
?>
}
<?
if (isset($_GET['num'])) { 
	$x = ob_get_contents();
	ob_end_clean();
	$t = preg_split('/[\r\n]{1,2}/',$x);
	echo "<ol>";
	for ($i = 0;$i<count($t);$i++) { 
		echo "<li>". htmlentities($t[$i]) . "</li>";
	}
}
?>
