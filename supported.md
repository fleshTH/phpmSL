Supported Constructs List
========

Identifiers
--------

Time & Date Identifiers
--------

- [x] $ctime
- [ ] $ctime(<format>)
- [x] $date & $date(<format>)
- [x] $time & $time(<format>)
- [x] $day
- [x] $daylight
- [x] $fulldate
- [x] $ticks
- [x] $gmt & $gmt(<timestamp>)
- [x] $duration(<seconds>[, <n>])
- [x] $uptime(<what>[, <n>])
- [x] $idle
- [ ] $asctime & $asctime(<n>[, <format>])
- [ ] $timestamp
- [ ] $timestmapfmt
- [ ] $ctimer
- [ ] $logstamp
- [ ] $logstampfmt
- [ ] $ltimer
- [ ] $online
- [ ] $timer(N/name)

File & Directory Identifiers
--------

- [ ] --

Nick & Address Identifiers 
--------

- [ ] --

Text & Number Identifiers
--------

- [x] $md5(<string>)
- [x] $base(<val>, <in>, <out>[, <pad>[, <pres>]])
- [x] $len(<string>)
- [x] $lower(<string>)
- [x] $upper(<string>
- [x] $left(<string>, <n>)
- [x] $right(<string>, <n>)
- [x] $mid(<string>, <start>[, <len>])
- [x] $str(<string>, <n>)
- [x] $qt(<string>)
- [x] $noqt(<string>)
- [x] $isupper(<string>)
- [x] $islower(<string>)
- [x] $replace(<string>, <what>, <with>[, ...])
- [x] $replacecs(<string>, <what>, <with>[, ...])
- [x] $replacex(<string>, <what>, <with>[, ...])
- [x] $remove(<string>, <what>[, ... ])
- [x] $encode(<string>[,<um>])
- [x] $decode(<string>[,<um>])
- [x] $floor(<val>)
- [x] $ceil(<val>)
- [x] $bytes(<val>, <opts>)
- [x] $strip(<string>[<bcur>])
- [x] $longip(<val>)
- [ ] $abs(<va>)
- [ ] $and(<val>, <val>)
- [ ] $asc(<character>)
- [ ] $biton(<val>, <n>)
- [ ] $bitoff(<val>, <n>)
- [ ] $chr(<val>)
- [ ] $compress(<what>, <opts>)
- [ ] $decompress(<what>, <opts>)
- [ ] $cos(<val>)
- [ ] $acos(<val>)
- [ ] $count(<string>, <what>[, ... ])
- [ ] $countcs(<string>, <what>[, ...])
- [ ] $isbit(<val>, <n>)
- [ ] $not(<val>)
- [ ] $or(<val>)
- [ ] $ord(<val>)
- [ ] $pos(<string>, <substring>[, <n>])
- [ ] $pocs(<string>, <substring>[, <n>])
- [ ] $wrap(<string>, <font>, <size>, <width>[, <word>], <n>)
- [ ] $xor(<val>, <val>)

Text & Number Identifiers
--------

- [x] $chr(<n>)
- [x] $int(<val>)
- [x] $log(<val>
- [x] $round(<val>, <p>)
- [x] $asin(<val>)
- [x] $sqrt(<val>
- [x] $atan(<val>)
- [x] $tan(<val>)
- [x] $rand(<a>,<b>)
- [x] $r(<a>,<b>)
- [x] $calc(<expression>)

Token Identifiers
--------

- [x] $gettok(<list>, <token>, <delimiter>)
- [x] $addtok(<list>, <token>, <delimiter>)
- [x] $numtok(<list>, <delimiter>)
- [x] $remtok(<list>, <token>, <delimiter>)
- [x] $remtokcs(<list>, <token>, <delimiter>)
- [x] $reptok(<list>, <token>, <delimiter>)
- [x] $reptokcs(<list>, <token>, <delimiter>)
- [x] $instok(<list>, <t>, <n>, <delimiter>)
- [x] $findtok(<list>, <token>, <n>, <delimiter>)
- [x] $findtokcs(<list>, <token>, <n>, <delimiter>)
- [x] $istok(<list>, <token>, <delimiter>)
- [x] $istokcs(<list>, <token>, <delimiter>)
- [x] $puttok(<list>, <token>, <delimiter>)
- [x] $sorttok(<list>, <delimiter>, <opts>)
- [x] $sorttokcs(<list>, <delimiter>, <opts>)
- [x] $deltok(<list>, <token>, <delimiter>)
- [x] $wildtok(<list>, <token>, <n>, <delimiter>)
- [x] $wildtokcs(<list>, <token>, <n>, <delimiter>)
- [x] $matchtok(<list>, <token>, <n>, <delimiter>)
- [x] $matchtokcs(<list>, <token>, <n>, <delimiter>)

Regular Expression Identifiers
--------

- [x] $regex(<string>, <pattern>)
- [x] $regsubex(<string>, <pattern>, <replace>)
- [x] $regml(<n>[, <string>])
- [ ] $regsub()

Hash Table
--------

- [x] $hget(<table>, <item>)

Window Identifiers
--------

Other Identifiers
--------