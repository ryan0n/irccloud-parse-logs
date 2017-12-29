# irccloud-parse-logs
A CLI utility written in PHP 7.2 to parse logs exported from IRC Cloud. <br />

Usage examples: <br />
bin/parse --zipFile=./irccloud-export.zip --exportDriver=Json  <br />
bin/parse --zipFile=./irccloud-export.zip --exportDriver=GenericOutput  <br />
bin/parse --zipFile=./irccloud-export.zip --exportDriver=MySQL  <br />
bin/parse --zipFile=./irccloud-export.zip --exportDriver=GenericOutput --searchPhrase=trump  <br />
bin/parse --zipfile=./irccloud-export.zip --exportdriver=genericoutput --searchphrase="donald trump"  <br />
