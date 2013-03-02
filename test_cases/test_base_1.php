<?php
    require_once "../lib/engine.php";
    
    $m = new mSL(); 
    $m->loadFile('
        alias test {
            echo Hello World
        }
    '); 
    $m->execScript("test");

?>
