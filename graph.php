#!/usr/bin/php
<?php

include "PHPlot/phplot/phplot.php";

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
$plot = new PHPlot(1200,800);

//Set titles
$plot->SetTitle(" [ WRK ] Hello World Web Server : Request/sec");
$plot->SetXTitle('Concurrency');
$plot->SetYTitle('Requests/Sec');

$plot->SetLegend($legend);
$plot->SetDataValues($request_data);

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
$plot->SetOutputFile($request_output);
$plot->SetFileFormat("png"); // is default anyway 

$plot->DrawGraph();


// ---------------------------------------------------------------------------------- //
// Avg Latency
// ---------------------------------------------------------------------------------- //
$plot = new PHPlot(1200,800);

//Set titles
$plot->SetTitle("[ WRK ] Hello World Web Server : avg latency");
$plot->SetXTitle('Concurrency');
$plot->SetYTitle('Avg Latency');

$plot->SetLegend($legend);
$plot->SetDataValues($avg_latency_data);

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
$plot->SetOutputFile($avg_latency_output);
$plot->SetFileFormat("png"); // is default anyway 

$plot->DrawGraph();

// ---------------------------------------------------------------------------------- //
// MAX Latency
// ---------------------------------------------------------------------------------- //
$plot = new PHPlot(1200,800);

//Set titles
$plot->SetTitle("Hello World Web Server : Max latency");
$plot->SetXTitle('Concurrency');
$plot->SetYTitle('Max Latency');

$plot->SetLegend($legend);
$plot->SetDataValues($max_latency_data);

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
$plot->SetOutputFile($max_latency_output);
$plot->SetFileFormat("png"); // is default anyway 

$plot->DrawGraph();

echo "http://10.2.3.105/result.html\n";

?>
