<? include("php-viking/viking.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Your description goes here" />
	<meta name="keywords" content="your,keywords,goes,here" />
	<meta name="author" content="Your Name" />
	<link rel="stylesheet" type="text/css" href="andreas09/andreas09.css" title="andreas09" media="screen,projection" />
	<title>Webuino v0.1</title>
        <? viking_7_script(1); ?>
</head>

<body onload = draw();>
<div id="container">
	<div id="sitename">
		<h1>Webuino v0.1 ------------> Arduino Simulator</h1>	
	</div>
	<div id="mainmenu">
		<ul>
                        <li><a href="index.php">Start</a></li>
			<li><a href="library.php">Sketch Library</a></li>
			<li><a href="simulation.php">Simulation</a></li>
			<li><a href="configuration.php">Configuration</a></li>
			<li><a href="advanced.php">Advanced</a></li>
			<li><a href="help.php">Help</a></li>
			<li><a href="about.php">About</a></li>
		</ul>
	</div>
 
	<div id="wrap">
		<div id="leftside">
			<h1>Included layouts</h1>
			<p>
			<a class="nav active" href="index.html">3 columns</a><span class="hide"> | </span>
			<a class="nav" href="2col.html">2 columns</a><span class="hide"> | </span>
			<a class="nav" href="#">Samplebutton</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 1</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 2</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 3</a>
<? viking_7_current(1); ?>
			</p>
			<h1>Included colors</h1>
			<img src="img/colors.jpg" height="104" width="125" class="thumbnail" alt="Included colors" />
		</div>

		<div id="rightside">
			<h1>Search</h1>
			<p class="searchform">
			<input type="text" alt="Search" class="searchbox" />
			<input type="submit" value="Go!" class="searchbutton" />
			</p>
	
			<h1>Latest news</h1>
			<p><strong>July 07, 2008:</strong><br />
It's been a while. But better late than never: New version, updated with refreshed sample content and simplified code. This is andreas09 v2.2!</p>

			<h1>Links:</h1>
			<ul class="linklist">
				<li><a href="http://simuino.com">Simuino</a></li>
				<li><a href="http://code.google.com/p/simuino">Project Site</a></li>
				<li><a href="http://arduino.cc">Arduino Official Site</a></li>
			</ul>
		</div>

		<div id="content" style="height:800px;">
                 <div style="float:left; width : 56%; background :white;">
                    <div id="simcontrol"><?viking_7_menu(1);?></div>
                    <div style="float:left; width :100%; background :white;"><?viking_7_canvas(1);?></div>
                    <div style="float:left; width :100%; background :white;"><?viking_7_winSerial(1);?></div>
                 </div>
                 <div style="float:right; width : 30%; background :yellow;">
                    <div style="float:left; width :100%; background :white;"><?viking_7_winLog(1);?></div>
		</div>
	<div class="clearingdiv">&nbsp;</div>
	</div>
</div>

<div id="footer">
	<p>&copy; 2012 <a href="#">Benny Saxen</a> | Template design by <a href="http://andreasviklund.com/">Andreas Viklund</a></p>
</div>

</body>
</html>
