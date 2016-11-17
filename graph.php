#!/usr/bin/php
<?php

include "PHPlot/phplot/phplot.php";

function WritePngGraphData( $sTitle, $sXTitle, $sYTitle, $rLegend, $rData, $sWriteFile ) {

	$plot = new PHPlot(1200,800);

	//Set titles
	$plot->SetTitle($sTitle);
	$plot->SetXTitle($sXTitle);
	$plot->SetYTitle($sYTitle);

	$plot->SetLegend($rLegend);
	$plot->SetDataValues($rData);

	//$plot->SetLineStyles();
	//$plot->SetImageBorderType('plain'); // Improves presentation in the manual
	$plot->SetPlotType('linepoints');
	$plot->SetDataType('text-data');
	$plot->SetDrawXDataLabelLines(True);

	$plot->SetLineWidths(2);
	$plot->SetPointSizes(10);
	$plot->SetLineStyles('solid');
	$plot->SetLegendUseShapes(True);
	//$plot->SetYTickLabelPos('none');
	//$plot->SetYTickPos('none');

	# X tick marks are meaningless with this data:
	//$plot->SetXTickPos('none');
	//$plot->SetXTickLabelPos('none');
	$plot->SetLegendPosition(0, 0, 'image', 0, 0, 150, 25);

	$plot->SetIsInline(True);
	$plot->SetOutputFile($sWriteFile);
	$plot->SetFileFormat("png"); // is default anyway 

	$plot->DrawGraph();

	return true;
}

$request_output		= "/var/www/html/result_request.png";
$avg_latency_output	= "/var/www/html/result_avg_latency.png";
$max_latency_output	= "/var/www/html/result_max_latency.png";

$legend				= array();
$rReqData			= array();
$rAvgLatencyData	= array();
$rMaxLatencyData	= array();
$files 				= array_filter(glob("./data/*"), 'is_file');
foreach ( $files as $idx => $sFile ) {

	$sKeyName	= basename($sFile, ".log");
	$legend[]	= $sKeyName;

	$lsLine		= explode("\n", file_get_contents("$sFile"));
	foreach ( $lsLine as $i => $sLine ) {
		if ( $sLine == "" ) { continue; }
		$lstemp	= explode(",", $sLine);
		$connection	= $lstemp[0];
//		if ( $connection == "" ) { continue; }
		$rReqData[$lstemp[0]][]			= $lstemp[1];	
		$rAvgLatencyData[$lstemp[0]][]	= $lstemp[2];	
		$rMaxLatencyData[$lstemp[0]][]	= $lstemp[3];	
	}
}

$request_data	= array();
foreach ( $rReqData as $c => $rList ) {
	$rItem	= array();
	$rItem[]	= $c;
	foreach ( $rList as $ix => $ss ) {
		$rItem[]	= $ss;
	}
	$request_data[]	= $rItem;
}

$avg_latency_data	= array();
foreach ( $rAvgLatencyData as $c => $rList ) {
	$rItem	= array();
	$rItem[]	= $c;
	foreach ( $rList as $ix => $ss ) {
		$rItem[]	= $ss;
	}
	$avg_latency_data[]	= $rItem;
}

$max_latency_data	= array();
foreach ( $rMaxLatencyData as $c => $rList ) {
	$rItem	= array();
	$rItem[]	= $c;
	foreach ( $rList as $ix => $ss ) {
		$rItem[]	= $ss;
	}
	$max_latency_data[]	= $rItem;
}

$nRet	= WritePngGraphData( "Hello World Web Server : Request/Sec", "Concurrency", "Request/sec", $legend, $request_data, $request_output );
$nRet	= WritePngGraphData( "Hello World Web Server : Request/Sec", "Concurrency", "Avg Latency", $legend, $avg_latency_data, $avg_latency_output );
$nRet	= WritePngGraphData( "Hello World Web Server : Request/Sec", "Concurrency", "Max Latency", $legend, $max_latency_data, $max_latency_output );

echo "http://10.2.3.105/result.html\n";

?>
