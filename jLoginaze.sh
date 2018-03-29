#! /bin/bash

#! /bin/bash

if [ "$#" -ne 1 ]; then
	echo "${0}: usage: jLoginaze.sh nameOfFileToJSONloginaze"
	exit 1
fi

cd "$(dirname "$0")"

#file=$(basename "$1")
file="${1%.*}"

sed -i "1s/^/<?php\nsession_start();\nif (isset($_SESSION['login_user']) == false || empty($_SESSION['login_user'])) {\nheader('Location:login.html');\n}\n?>\n/" $1
mv $1 $file".php"
