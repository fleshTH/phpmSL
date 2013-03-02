<?php
    require_once "../lib/engine.php";

    $s = ms_start();
    
    $m = new mSL(); 
    $m->loadFile('
        Alias TokenTest {
            var %a = t i
            var %b = of best the times was
            var %c = us we everything had before
            var %d = directly Heaven0000 going
            var %string = $remove($sorttok($reptokcs(%a,i,I,1,32),32),$chr(32)) $gettok($sorttok(%b,32,r),1,32) $gettok(%b,3,32) $sorttok($gettok(%b,1-2,32),32) $remove(%a me $chr(44),$chr(32))
            var %string = %string $gettok(%string,1-3,32)  worst $gettok(%string,5,32) $remove($gettok(%string,6,32) ;,$chr(32),$chr(44))
            var %string2 = $gettok(%string,1-3,32) $remove(a $sorttok(g e,32,r),$chr(32)) $sorttok(of $remove(wisdom $chr(44),$chr(32)),32) $gettok(%string,1-3,32) $remove(a $sorttok(g e,32,r),$chr(32)) $sorttok(of foolishness;,32,r)
            var %string3 = $gettok(%string,1-3,32) $sorttok($replace($remove(o.f._.e.p.o.c.h,.),_,$chr(32)),32) $remove(belief $chr(44),$chr(32))
            var %string3 = %string3 $gettok(%string3,1-5,32) incredulity;
            var %string4 = $gettok(%string,1-3,32) season of $replace(Light_,_,$chr(44))
            var %string4 = %string4 $replace(%string4,$replace(Light_,_,$chr(44)),Darkness;)
            var %string5 = $gettok(%string,1-3,32) $replace($gettok($sorttok(hope_ Hello There!! spring of,32,r),2-4,32),_,$chr(44))
            var %string5 = %string5 $gettok(%string,1-3,32) $sorttok(despair; winter $remove($sorttok(o f,32,r),$chr(32)),32,r)
            var %string6 = $gettok($sorttok(%c,32,r),1,32) $gettok($sorttok(%c,32,r),3-,32) $replace($remove($gettok(%c,1,32) _,$chr(32)),_,$chr(44))
            var %string6 = %string6 $replace($gettok(%string6,1-4,32),everything,nothing) $replace($remove($gettok(%string6,5,32) ???,$chr(32),$chr(44)),???,;)
            var %string7 =  $gettok(%string6,1,32) $sorttok($replace($remove(a l l + w e r e,$chr(32)),+,$chr(32)),32,r) $gettok($sorttok(%d,32,r),2-,32) to $replace($gettok($sorttok(%d,32,r),1,32),0000,$chr(44))
            var %string7 = %string7 $gettok(%string7,1-4,32)  the other way.
            
            echo "
            echo string: %string
            echo string2: %string2
            echo string3: %string3
            echo string4: %string4
            echo string5: %string5
            echo string6: %string6
            echo string7: %string7
            echo "
            echo -- Charles Dickens
            
            ; "
            ; string: It was the best of time, It was the worst of time;
            ; string2: It was the age of wisdom, It was the age of foolishness;
            ; string3: It was the epoch of belief, It was the epoch of incredulity;
            ; string4: It was the season of Light, It was the season of Darkness;
            ; string5: It was the spring of hope, It was the winter of despair;
            ; string6: we had everything before us, we had nothing before us;
            ; string7: we were all going directly to Heaven, we were all going the other way.
            ; "
            ; -- Charles Dickens
        }
    '); 
    $m->execScript("TokenTest");
    
    
    echo "\nTook: ", ms_end($s), "\n\n";
?>
