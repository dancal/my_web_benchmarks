#!/usr/bin/php
<?php

/*
Running 10s test @ http://10.1.5.201:7000/request
  16 threads and 128 connections
  Thread Stats   Avg      Stdev     Max   +/- Stdev
    Latency    10.04ms   77.51ms   1.36s    97.91%
    Req/Sec    10.24k   837.13    13.19k    79.75%
  1620660 requests in 10.10s, 187.02MB read
Requests/sec: 160460.23
Transfer/sec:     18.52MB
Socket errors: connect 0, read 2, write 1659, timeout 0
*/
function convert_time( $time ) {
	if ( strpos($time,'us') !== false ) {
		$time = str_replace('us','',$time) / 1000;
	} else if ( strpos($time,'ms') !== false ) {
		$time = str_replace('ms','',$time);
	} else if ( strpos($time,'s') !== false ) {
		$time = str_replace('s','',$time) * 1000;
	} else if ( strpos($time,'m') !== false ) {
		$time = str_replace('m','',$time) * 1000 * 60;
	}
	return $time;
}

// 
$threads		= 0;
$connections	= 0;
$avgLatency		= 0;
$maxLatency		= 0;
$req_per_sec	= 0;
$error_line		= '';
$sTestLine		= '';
while($line = fgets(STDIN)){

    $sLine      = trim($line);
	$sLine		= str_replace( array('    ','   ', '  ',"\t"), " ", $sLine);
	if ( strpos($sLine,'threads and') !== false && strpos($sLine,'connections') !== false ) {
		$lstemp	= explode(" ", $sLine);
		$threads		= trim($lstemp[0]);
		$connections	= trim($lstemp[3]);
	}

	if ( strpos($sLine,'Latency') !== false ) {
		$lstemp	= explode(" ", $sLine);
		$avgLatency	= trim($lstemp[1]);
		$maxLatency	= trim($lstemp[3]);
	}
	if ( strpos($sLine,'Requests/sec') !== false ) {
		$lstemp	= explode(":", $sLine);
		$req_per_sec	= trim($lstemp[1]);
	}
	if ( strpos($sLine,'Socket errors:') !== false ) {
		// Socket errors: connect 0, read 2, write 1659, timeout 0
		$lstemp	= explode(":", $sLine);
		$error_line	= trim($lstemp[1]);
	}
	if ( strpos($sLine,'requests in') !== false ) {
		$sTestLine	= $sLine;
	}
	
}
 
$avgLatency	= convert_time( $avgLatency );
$maxLatency	= convert_time( $maxLatency );

/*
echo "\n";
echo "connections : $connections\n";
echo "threads : $threads\n";
echo "avgLatency : $avgLatency\n"; 
echo "maxLatency : $maxLatency\n"; 
echo "Requests/sec : $req_per_sec\n";
echo "$sTestLine\n";
*/
echo "$connections,$req_per_sec,$avgLatency,$maxLatency,$error_line,$sTestLine\n";
?>
