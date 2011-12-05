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
