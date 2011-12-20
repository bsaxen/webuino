<? include("php-viking/viking.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Your description goes here" />
	<meta name="keywords" content="your,keywords,goes,here" />
	<meta name="author" content="Your Name" />
	<link rel="stylesheet" type="text/css" href="andreas09/andreas09.css" title="andreas09" media="screen,projection" />
	<title>Webuino v0.2</title>
</head>

<body>
<div id="container">
	<div id="sitename">
		<h1>Webuino v0.2<h1>
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
                        <li><? viking_4_login_logout(); ?></li>
		</ul>
	</div>
 
	<div id="wrap">
		<div id="leftside">
			<h1>User: <? viking_4_showUserLoggedIn(); ?></h1>
			<p>
			<a class="nav active" href="index.html">3 asasd<span class="hide"> | </span>
			<a class="nav" href="2col.html">2 columns</a><span class="hide"> | </span>
			<a class="nav" href="#">Samplebutton</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 1</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 2</a><span class="hide"> | </span>
			<a class="nav sub" href="#">Sub-page 3</a>
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
                                <li><? viking_4_addUser_Link(); ?></li>
			</ul>
		</div>

		<div id="content">
                    <? viking_4_login_Form(); ?>
                     <? viking_4_adduser_Form(); ?>
		</div>
	<div class="clearingdiv">&nbsp;</div>
	</div>
</div>

<div id="footer">
   <? viking_lib_showError(); viking_lib_showWarning(); ?>
	<p>&copy; 2012 <a href="#">Benny Saxen</a> | Template design by <a href="http://andreasviklund.com/">Andreas Viklund</a></p>
</div>

</body>
</html>
