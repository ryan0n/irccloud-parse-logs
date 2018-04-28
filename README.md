# irccloud-parse-logs
A CLI utility written in PHP 7.2 to parse logs exported from IRC Cloud. <br />

Usage examples: <br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=Json<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=RawOutput<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=MySQL<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=RawOutput --searchPhrase=trump<br />
php bin/parse.php --zipfile=./irccloud-export.zip --exportdriver=RawOutput --searchphrase="donald trump"<br />
<br />
Export driver 'RawOutputOneLine' is the chosen default when --exportDriver isn't present.<br />
The date + time of the log entries can be filtered with --date.<br />
php bin/parse.php --zipfile=./irccloud-export.zip --contextLines=50 --searchphrase="donald trump" --date=2017-06-12<br />
php bin/parse.php --zipfile=./irccloud-export.zip --contextLines=50 --searchphrase="donald trump" --date=2017-06<br />
php bin/parse.php --zipfile=./irccloud-export.zip --contextLines=50 --searchphrase="donald trump" --date=2017<br />
