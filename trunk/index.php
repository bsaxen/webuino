<? include("pv.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Your description goes here" />
	<meta name="keywords" content="your,keywords,goes,here" />
	<meta name="author" content="Your Name" />
	<link rel="stylesheet" type="text/css" href="index.css" title="webuino" media="screen,projection" />
	<title>Webuino v0.5</title>
        <? pv(); ?>
</head>

<body onload = draw();>
<div id="holder" style="float:left; width : 100%; background :white;">
	<div id="header" style="float:left; width : 100%; background :#84ACC9;">
		<h1>Webuino v0.5 Arduino Simulator</h1>	
	</div>

	<div id="mainmenu">
		<ul>
                        <li><a href="index.php?pv=start">Start</a></li>
			<li><a href="index.php?pv=lib">Sketch Library</a></li>
			<li><a href="index.php?pv=sim">Simulation</a></li>
			<li><a href="index.php?pv=config">Configuration</a></li>
			<li><a href="index.php?pv=advanced">Advanced</a></li>
			<li><a href="index.php?pv=help">Help</a></li>
			<li><a href="index.php?pv=about">About</a></li>
			<li><a href="index.php?pv=register">Register</a></li>
			<li><a href="index.php?pv=pv">PV</a></li>
                        <li><? pv(); ?></li>
                        <li><? pv(); ?></li>
                        <li><? pv(); ?></li>
		</ul>
	</div>

	<div id="wrap" style="float:left; width : 100%; background :white;">
		<div id="left" style="margin-right:10px;float:left; width : 22%; background :white;">
                   <? pv(); ?><? pv(); ?><? pv(); ?>
		</div>


		<div id="middle" style="float:left; width : 50%; background :white;">
                    <div id="simcontrol"><? pv(); ?></div>
                    <div style="float:left; width :100%; background :white;"><? pv(); ?></div>
                    <div style="float:left; width :100%; background :white;"><? pv(); ?></div>
		</div>

		<div id="right" style="margin-left:10px;float:left; width : 22%; background :white;">
                   <? pv(); ?><? pv(); ?><? pv(); ?>
	        </div>
        </div>
</div>

<div id="footer"><? pv(); ?><br><? pv(); ?>
    <p>&copy; 2012 <a href="#">Benny Saxen</a></p>
</div>

</body>
</html>
