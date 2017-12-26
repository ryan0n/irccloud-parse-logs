# irccloud-parse-logs
A CLI utility written in PHP to parse logs exported from IRC Cloud.

Usage examples:
bin/parse --zipFile=./irccloud-export.zip --exportDriver=Json
bin/parse --zipFile=./irccloud-export.zip --exportDriver=GenericOutput
bin/parse --zipFile=./irccloud-export.zip --exportDriver=MySQL
bin/parse --zipFile=./irccloud-export.zip --exportDriver=GenericOutput --searchPhrase=trump
