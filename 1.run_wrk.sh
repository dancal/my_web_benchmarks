#!/bin/sh

CONNECTIONS="16 32 64 128 256 300 400 500 600 700 800 900 1024"

run_wrk() {

	CONNECTIONS=$1
	TYPE=$2
	URL=$3

	rm -f ./data/${TYPE}.log
	for con in $CONNECTIONS
	do
		COMMAND="./wrk/wrk -t16 -c${con} -d60s $URL"
		echo "$COMMAND";
		./wrk/wrk -t16 -c${con} -d60s $URL | ./_parser_wrk.php >> ./data/${TYPE}.log
		sleep 5
	done

	sleep 5
}

# ------------------------------------------------------------------------- #
TYPE="php7_dancal"
URL="http://10.1.5.201/request.php"
run_wrk "$CONNECTIONS" "$TYPE" "$URL"

# ------------------------------------------------------------------------- #
TYPE="scala_dancal"
URL="http://10.1.5.201:7000/request"
run_wrk "$CONNECTIONS" "$TYPE" "$URL"

# ------------------------------------------------------------------------- #
TYPE="rust_dancal"
URL="http://10.1.5.201:7001/request"
run_wrk "$CONNECTIONS" "$TYPE" "$URL"

# ------------------------------------------------------------------------- #
TYPE="cpp_dancal"
URL="http://10.1.5.201:7002/request"
run_wrk "$CONNECTIONS" "$TYPE" "$URL"

exit;

# ------------------------------------------------------------------------- #
#TYPE="lua_chungsik"
#URL="http://10.1.5.201:9500/lua/shared/count/incr"

# ------------------------------------------------------------------------- #
#TYPE="spring_sanmgho"
#URL="http://10.1.5.201:9700/request"

# ------------------------------------------------------------------------- #
#TYPE="go_hyuntea"
#URL="http://10.1.5.201:5000/request"

# ------------------------------------------------------------------------- #
#TYPE="vertx_sungil"
#URL="http://10.1.5.201:6500/request"

# ------------------------------------------------------------------------- #
#TYPE="go_dongok"
#URL="http://10.1.5.201:5500/request"

# ------------------------------------------------------------------------- #
#TYPE="go_ewcho"
#URL="http://10.1.5.201:9000/request"

rm -f ./data/${TYPE}.log
for con in $CONNECTIONS
do

	COMMAND="./wrk/wrk -t16 -c${con} -d60s $URL"
	echo "$COMMAND";
	./wrk/wrk -t16 -c${con} -d60s $URL | ./_parser_wrk.php >> ./data/${TYPE}.log
	sleep 5

done
