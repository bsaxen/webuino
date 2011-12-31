<? include("pv.php"); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<meta name="description" content="Your description goes here" />
	<meta name="keywords" content="your,keywords,goes,here" />
	<meta name="author" content="Your Name" />
	<link rel="stylesheet" type="text/css" href="index.css" title="webuino" media="screen,projection" />
	<title>Webuino v0.0.3</title>
        <? pv(); ?>
</head>

<body onload = draw();>

<div id="header" style="float:left; width : 100%; height:80px; background :white;">  
   <div id="logo" style="margin-left:3px;float:left;  background :white; width:65%;">  
    <img src="simuino.jpg" alt="simuino" height=80px width=100%>
   </div>
   <div id="rend" style="margin-left:3px;float:left;  background :white; height:100%; width:25%;">  
     <? pv(); ?>
   </div>
</div>
<div id="menu" style="float:left; width : 100%; height:28px; background :#bdbdbd;">    
    <? pv(); ?> <li><? pv(); ?></li>
</div>
<div id="container" style="float:left; width:100%; background:white;margin-top:5px;">
	<div id="left" style="margin-left:10px;float:left;  background :white; width:55px;">
          <div id="nav-menu">    
             <? pv(); ?>
           </div>
	</div>
	<div id="middle" style="margin-left:10px;margin-right:10px;float:left;width:47%; background:white;">
                   <? pv(); ?><? pv(); ?><? pv(); ?>
	</div>

	<div id="right" style="margin-left:10px;float:left;  background:white; width:40%">
                   <? pv(); ?>
	</div>
	<div id="below" style="margin-left:10px;margin-right:10px;float:left;  background:white; width:98%">
                   <? pv(); ?>
	</div>
</div>

<div id="footer" style="float:left; width : 100%; background :grey;">
    User:<b><? pv(); ?></b> &copy; 2012 Simuino Team<br><? pv(); ?><br><? pv(); ?><br><? pv(); ?>
</div>
<div id="error" style="float:left; width : 100%; background :white;">
    <? pv(); ?><br><? pv(); ?>
</div>

</body>
</html>
