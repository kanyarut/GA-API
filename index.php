<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/> 
<title>Google Analytic API Example</title>
<link rel="stylesheet" href="style.css" />
</head>
<body>
<div id="container">
<?
include_once('gaapi.class.php');
$ga=new gaApi('username','password','ga:site id');
$now=date("Y-m-d");
$lastmonth=date('Y-m-d', strtotime('-30 days'));

//Summery: visitors, unique visit, pageview, time on site, new visits, bounce rates
$summery=$ga->getSummery($lastmonth,$now);

//All time summery: visitors, page views
$allTimeSummery=$ga->getAllTimeSummery();

//Last 10 days visitors (for graph)
$visits=$ga->getVisits($lastmonth,$now,10);

//Top 10 search engine keywords
$topKeywords=$ga->getTopKeyword($lastmonth,$now,10);

//Top 10 visitor countries
$topCountries=$ga->getTopCountry($lastmonth,$now,10);

//Top 10 page views
$topPages=$ga->getTopPage($lastmonth,$now,10);

//Top 10 referrer websites
$topReferrer=$ga->getTopReferrer($lastmonth,$now,10);

//Top 10 visitor browsers
$topBrowsers=$ga->getTopBrowser($lastmonth,$now,10);

//Top 10 visitor operating systems
$topOs=$ga->getTopOs($lastmonth,$now,10);
?>
<div id="vGA">
<div><?= $allTimeSummery['ga:pageviews'] ?>
       <span id="vGALabel">Views</span>
    </div>
</div>
<h1>Visitors Overview</h1>
<img src="http://chart.apis.google.com/chart?
chs=460x200
&amp;chg=22,30&amp;chd=t:<?
$i=0;
$max=0;
$min=100000;
foreach($visits as $v){
	if($i>0)echo ",";
	if($v['ga:visits']>$max)$max=$v['ga:visits'];
	if($v['ga:visits']<$min)$min=$v['ga:visits'];
	echo $v['ga:visits'];
	$i++;
}
$max=$max+5;
$min=$min-5;
?>
&amp;chl=<?
$i=0;
foreach($visits as $v){
	if($i>0)echo "|";
	$tmp=null;
	$tmp[]=substr($v['ga:date'], -2);
	$tmp[]=substr($v['ga:date'], -4,2);
	//echo $v['ga:date'];
	echo "$tmp[0]/$tmp[1]";
	$i++;
}
?>
&amp;chxr=1,<?= $min ?>,<?= $max ?>
&amp;chds=<?= $min ?>,<?= $max ?>
&amp;chm=o,0066FF,0,-1.0,6|N,0066FF,0,-1.0,11
&amp;chxt=x,y
&amp;cht=lc
" />

<h1>Last 30 Days</h1>
<table>
<tr><th>visits</th><td><?= $summery['ga:visits'] ?></td></tr>
<tr><th>unique visitors</th><td><?= $summery['ga:visitors'] ?></td></tr>
<tr><th>pageviews</th><td><?= $summery['ga:pageviews'] ?></td></tr>
<tr><th>time on site</th><td><?= floor(($summery['ga:timeOnSite']/$summery['ga:visits'])/60) . ":" . ($summery['ga:timeOnSite']/$summery['ga:visits']) % 60 ?></td></tr>
<tr><th>new visits</th><td><?= ceil(($summery['ga:newVisits']/$summery['ga:visits'])*100) ?> %</td></tr>
<tr><th>bounce rate</th><td><?= ceil(($summery['ga:bounces']/$summery['ga:entrances'])*100)?> %</td></tr>
</table>

<h1>All Time</h1>
<table>
<tr><th>visits</th><td><?= $allTimeSummery['ga:visits'] ?></td></tr>
<tr><th>pageviews</th><td><?= $allTimeSummery['ga:pageviews'] ?></td></tr>
</table>

<h1>Top Keyword</h1>
<table>
<? foreach($topKeywords as $keyword){ ?>
<tr><th><?= $keyword['ga:keyword'] ?></th><td><?= $keyword['ga:visits'] ?></td></tr>
<? } ?>
</table>

<h1>Top Country</h1>
<table>
<? foreach($topCountries as $country){ ?>
<tr><th><?= $country['ga:country'] ?></th><td><?= $country['ga:visits'] ?></td></tr>
<? } ?>
</table>

<h1>Top Page View</h1>
<table>
<? foreach($topPages as $page){ ?>
<tr><th><div><?= $page['ga:pagePath'] ?></div></th><td><?= $page['ga:visits'] ?></td></tr>
<? } ?>
</table>

<h1>Top Referrer</h1>
<table>
<? foreach($topReferrer as $ref){ ?>
<tr><th><div><?= $ref['ga:source'] ?></div></th><td><?= $ref['ga:visits'] ?></td></tr>
<? } ?>
</table>

<h1>Top Browser</h1>
<img src="http://chart.apis.google.com/chart?
chs=460x160
&amp;chd=t:<?
$i=0;
foreach($topBrowsers as $browser){
	if($i>0)echo ",";
	echo $browser['ga:visits'];
	$i++;
}
?>
&amp;chl=<?
$i=0;
foreach($topBrowsers as $browser){
	if($i>0)echo "|";
	echo $browser['ga:browser'];
	$i++;
}
?>
&amp;cht=p3
" />


<h1>Top OS</h1>
<img src="http://chart.apis.google.com/chart?
chs=460x160
&amp;chd=t:<?
$i=0;
foreach($topOs as $os){
	if($i>0)echo ",";
	echo $os['ga:visits'];
	$i++;
}
?>
&amp;chl=<?
$i=0;
foreach($topOs as $os){
	if($i>0)echo "|";
	echo $os['ga:operatingSystem'];
	$i++;
}
?>
&amp;cht=p3
" />
</div><!--end container-->
</body>
</html>