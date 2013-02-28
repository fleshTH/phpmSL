
9/18/09
============================================
Notes:
  added hash table support
  fixed issues with $regsubex
  added socket support
  added more support for events
  fixed bugs with variables not staying in scope
  







9/03/09
==============================================================
Notes/change log

-Made the parser into a class. I have changes a few of the method names
 for example, it's no longer parseMlines and parseMirc,
 parseMlines is now execScript. parseMirc is now execLine.

<?
 $m = new mSL();
 $m->loadScript($_post['data']);
 $m->execScript("test"); // to execute the alias named test.
?>

-alias are stored in a static member of the mSL class.
 so, to access it, just use mSL::aliases.

-added all functions to it's own class. when checking if a function exists use this

<?
 if (method_exists($this->function,$funcName)) { 
      call_user_func_array(Array($this->function,$funcName),$args);
 }
?>

- i have also added a $stackNumber (didn't know what else to call it) for execLine
  this will allow for accessing $prop and $isid which will need to be checked for
  internal commands as well. $isid for /remove and $remove. 
  to check for this you need to do the following:


<?
 function myFunction() { 
   $localParams = $this->parent->getLocalParams();
   $p = $localParams['prop'];
   if ($p == "data") { 
       return "p = data";
   }
 }

?>

  and the same for $isid. Also worth noting that the 'parent' class mSL is in functions::$parent.
  so calling $this->parent from the functions class will get the mSL class instance.

- added support for $v1 $v2 $ifmatch and the like. these are stored mSL::$_scope (not static).
  the scope variable is an assoc array. so far it uses $_scope['variables'] and $_scope['defined']
  the $_scope['defined'] is where you will find $v1 $v2 ect... it will also be where event
  specific identifiers will be such as $nick $chan ect.

- conditions.php has been removed. i wanted it needed to be in the class to have access to
  connection specific info like ischan ect. it exists in the mSL class.

