# irccloud-parse-logs
A CLI utility written in PHP 7.2 to parse logs exported from IRC Cloud. <br />

Usage examples: <br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=Json<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=MySQL<br />
php bin/parse.php --zipFile=./irccloud-export.zip --exportDriver=GenericOutput --searchPhrase=trump<br />
php bin/parse.php --zipfile=./irccloud-export.zip --exportdriver=genericoutput --searchphrase="donald trump"<br />
php bin/parse.php --zipfile=./irccloud-export.zip --searchphrase="donald trump" --contextLines=50 --date=2017-06-12<br />
