<?php

$currentStep = $_POST["step"];
if($_POST["step"] == "")
  echo "No command given";
 else
   {
     //echo "Command ".$_POST["name"];
     
     $in = fopen("data.su", 'r');
     while (!feof($in)) 
       {
	 $row = fgets($in);
	 if($row[0] != '#')
	   {
	     //$left = strpos($row,"\"");
	     //$right = strpos($row,"\"",$left);
	     //$length = $left - $right;
	     //$res = substring($row,$left,$length);
	     sscanf($row, "%d %s", $step, $command);
	     if($step == $currentStep)echo("$command ($step)<br>");
	   }
       }
     fclose($in);
   }

?>
<script>
<!--

/*
Auto Refresh Page with Time script
By JavaScript Kit (javascriptkit.com)
Over 200+ free scripts here!
*/

//enter refresh time in "minutes:seconds" Minutes should range from 0 to inifinity. Seconds should range from 0 to 59
var limit="0:30"

if (document.images){
var parselimit=limit.split(":")
parselimit=parselimit[0]*60+parselimit[1]*1
}
function beginrefresh(){
if (!document.images)
return
if (parselimit==1)
window.location.reload()
else{ 
parselimit-=1
curmin=Math.floor(parselimit/60)
cursec=parselimit%60
if (curmin!=0)
curtime=curmin+" minutes and "+cursec+" seconds left until page refresh!"
else
curtime=cursec+" seconds left until page refresh!"
window.status=curtime
setTimeout("beginrefresh()",1000)
}
}

window.onload=beginrefresh
//-->
</script>