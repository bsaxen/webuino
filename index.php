<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Ajax - PHP example</title>
</head>
 
<body>

<div align="center" id="timeval">--:--:--</div>
<button id="stop">Stop</button>

<script src="jquery.js"></script> 
<script language="javascript" type="text/javascript">
<!-- 


$(document).ready(function()
{
   //ajaxTime.php is called every second to get time from server
   var refreshId = setInterval(function()
   {
     $('#timeval').load('ajax.php?randval='+ Math.random());
   }, 1000);

   //stop the clock when this button is clicked
   $("#stop").click(function()
   {
     clearInterval(refreshId);
   });
});

// Get the HTTP Object
function getHTTPObject(){
   if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
   else if (window.XMLHttpRequest) return new XMLHttpRequest();
   else {
      alert("Your browser does not support AJAX.");
      return null;
   }
}   
 
// Change the value of the outputText field
function setOutput(){
    if(httpObject.readyState == 4){
        document.getElementById('outputText').value = httpObject.responseText;
    }
 
}
 
// Implement business logic    
function doWork(){    
    httpObject = getHTTPObject();
    if (httpObject != null) {
        httpObject.open("ajax.php?inputText="
                        +document.getElementById('inputText').value, true);
        httpObject.send(null); 
        httpObject.onreadystatechange = setOutput;
    }
}
 
var httpObject = null;
 
//-->
</script>
  <form name="testForm">
     Command: <input type="text"  onkeyup="doWork();" name="inputText" id="inputText" /><br> 
     <input type="text" name="outputText" id="outputText" size=50/>
     <input type="text" name="outputText" id="analogPins" size=50/>
  </form>
<div  style="background:green; height:500px; width:100%;">
   <div style=" background:red;">
a
   </div>
b
   <div style=" background:white; float:left; width:500px">
      <div style=" background:black; height:40px;">
a
      </div>
      <img src="ArduinoUno_r2_front.jpg" alt="alt text" width=500/>
      <div style=" background:black; height:40px;">
a
      </div>
   </div>

   <div style="background:yellow; float:left; width:100%">
d   
   </div>
   <div style="background:blue; float:right; width:150px">
e   
   </div>
</div>
</body>
</html>
