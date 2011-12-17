<?
$upload   = 'upload/';
$servuino = 'servuino/';

$simulation = array();
$content    = array();
$status     = array();
$serial     = array();
$serialL    = array();

$pinValueA  = array();
$pinValueD  = array();
$pinStatusA = array();
$pinStatusD = array();
$pinModeD   = array();

//==========================================
function copySketch($sketch)
//==========================================
{
  global $upload;
  $sketch = $upload.$sketch;
  if (!copy($sketch,"servuino/sketch.pde")) {
    echo "failed to copy ($sketch)...<br>";
  }
}

//==========================================
function compileSketch()
//==========================================
{
  system("cd servuino;g++ -o servuino servuino.c > g++.error 2>&1;");
}

//==========================================
function execSketch($steps,$source)
//==========================================
{
  system("cd servuino;./servuino $steps $source >exec.error 2>&1;");
}

//==========================================
function decodeStatus($code)
//==========================================
{
  global $curStep,$pinValueA,$pinValueD,$pinStatusA,$pinStatusD,$pinModeD;

  $par = array();
  $tok = strtok($code, ",");
  $par[0] = $tok;
  if($tok != $curStep)
    {
      echo("Sync Error Step: $step - $currentStep<br>");
      return;
    }
  $ix = 0;
  while ($tok !== false) {
    $ix++;
    //echo "Word=$tok<br />";
    $tok = strtok(",");
    $par[$ix] = $tok;
  }

  // Mode Digital Pin
  $temp = $par[1];
  $bb = strlen($temp);
  for($ii=0;$ii<strlen($temp);$ii++)
    {
      if($temp[$ii]=='-')$pinModeD[$ii] = BLACK;
      if($temp[$ii]=='O')$pinModeD[$ii] = YELLOW;
      if($temp[$ii]=='I')$pinModeD[$ii] = WHITE;
      if($temp[$ii]=='X')$pinModeD[$ii] = RED;
      if($temp[$ii]=='Y')$pinModeD[$ii] = GREEN;
      if($temp[$ii]=='C')$pinModeD[$ii] = BLUE;
      if($temp[$ii]=='R')$pinModeD[$ii] = FUCHSIA;
      if($temp[$ii]=='F')$pinModeD[$ii] = AQUA;
    }

  // Status Digital Pin
  $temp = $par[2];

  $ii = 0;
  $values = array(1,2,4,8,16,32,64,128,256,512,1024,2048,4096,8192);
  foreach ($values as $value) 
    {
      $result = $value & $temp;
      //print("$result, $value, '&', $temp<br>");
      if($result != 0) 
	$pinStatusD[$ii] = YELLOW;
      else
	$pinStatusD[$ii] = BLACK;
      $ii++;	   
    }

  // Status Analog Pin
  $tempA = $par[3]; // Number of Analog Values
  if($tempA > 0)
    {
      for($ii=0;$ii<$tempA;$ii++)
	{
	  $ix = 5+$ii*2;
	  $pinValueA[$par[$ix]] = $par[$ix+1];
	  $aw = $par[$ix]; 
          $qq = $par[$ix+1];
	  if($pinValueA[$par[$ix]]> 0)$pinStatusA[$par[$ix]] = YELLOW;
	  //echo("$tempA Analog $ii $aw $qq<br>");
	}
    }
  $tempD = $par[4]; // Number of Digital Values
  if($tempD > 0)
    {
      for($ii=0;$ii<$tempD;$ii++)
	{
	  $ix = 5+$ii*2+2*$tempA;
	  $pinValuesD[$par[$ix]] = $par[$ix+1];
	  $aw = $par[$ix]; 
          $qq = $par[$ix+1];
	  if($pinValueA[$par[$ix]]> 0)$pinStatusD[$ix] = RED;
	  //echo("$tempD Digital $ii $aw $qq<br>");
	}
    }
}
//==========================================
function show($step)
//==========================================
{
  global $simulation;
  echo("$simulation[$step]<br>");
}

//==========================================
function init($steps)
//==========================================
{
  for($ii=0;$ii<=$steps;$ii++)
    {
      $simulation[$ii] = "";
      $serial[$ii] = "";
      $serialL[$ii] = "";
    }
}

//==========================================
function showStep($target)
//==========================================
{
  global $curStep,$simulation,$curSimLen;


  for($ii=$target+1;$ii>0;$ii--)
    {
      if($ii==$target+1)
       echo("next> $simulation[$ii]<br>");
      else if($ii==$target)
	echo("now> $simulation[$ii]<br>");
      else
	echo("<a href=\"index.php?ac=step&x=$ii\">$simulation[$ii]</a><br>");
    }
}

//==========================================
function showSerial($target)
//==========================================
{
  global $curStep,$serial,$serialL;

  $stemp = array();

  $jj = 1;
  //for($ii=$target;$ii>0;$ii--)
  for($ii=1;$ii<=$target;$ii++)
    {
      $temp = $serial[$ii];
      if($serialL[$ii] == 'NL')
	{
	  $stemp[$jj] = $stemp[$jj]."<a href=\"index.php?ac=step&x=$ii\">$temp</a><br>";
	  $jj++;
	  $flag = 0;
	  //echo("<a href=\"index.php?ac=step&x=$ii\">$temp</a><br>");
	}
      if($serialL[$ii] == 'SL')
	{
	  $stemp[$jj] = $stemp[$jj]."<a href=\"index.php?ac=step&x=$ii\">$temp</a>";
	  $flag = 1;
	  //echo("<a href=\"index.php?ac=step&x=$ii\">$temp</a>");
	}
    }


  //for($ii=1;$ii<=$jj;$ii++)
 
   for($ii=$jj;$ii>0;$ii--)
    {
      $temp = $stemp[$ii];
      echo("$temp");
      if($flag==1 && $ii == $jj)echo("<br>");
    }

}

//==========================================
function showSimulation($target)
//==========================================
{
  global $curStep,$simulation,$curSimLen;


  for($ii=1;$ii<=$curSimLen;$ii++)
    {
      if($ii==$target)
	echo("now> $simulation[$ii]<br>");
      else
	echo("<a href=\"index.php?ac=step&x=$ii\">$simulation[$ii]</a><br>");
    }
}

//==========================================
function showAnyFile($target)
//==========================================
{
  global $content;

  for($ii=1;$ii<=$target;$ii++)
    {
      echo("$content[$ii]<br>");
    }
}

//==========================================
function readSimulation($file)
//==========================================
{
  global $simulation,$servuino,$curSimLen;

  $file = $servuino.$file;
  $step = 0;
  $in = fopen($file,"r");
  if($in)
    {
      while (!feof($in))
	{
	  $row = fgets($in);
	  $row = trim($row);
	  //$row = safeText($row);
	  //echo("$row<br>");
	  if($row[0]=='+')
	    {
	      $step++;
              $row[0] = ' ';
	      $simulation[$step] = $row;
	      //echo("$row<br>");
	    }
	}
      $curSimLen = $step;
      fclose($in);
    }
  else
    echo("Fail to open $file<br>");
  return($step);
}

//==========================================
function readSerial($file)
//==========================================
{
  global $serial,$servuino,$serialL;

  $file = $servuino.$file;
  $step = 0;
  $in = fopen($file,"r");
  if($in)
    {
      while (!feof($in))
	{
	  $row = fgets($in);
	  $row = trim($row);
	  sscanf($row,"%d %s %s",$step,$line,$value);
	  $serialL[$step] = $line;
	  $left  = strpos($row,"[");
	  $right = strpos($row,"]");
	  if($right && $left){$left++;$value = substr($row,$left,$right-$left);}
	  $value = safeText($value);
	  //echo("$step $line $value<br>");
	  $serial[$step] = $value;
	}
      fclose($in);
    }
  else
    echo("Fail to open $file<br>");
  return($step);
}

//==========================================
function readStatus()
//==========================================
{
  global $status,$servuino;

  $file = $servuino.'data.status';
  $step = 0;
  $in = fopen($file,"r");
  if($in)
    {
      $row = fgets($in);
      $row = fgets($in);
      while (!feof($in))
	{
	  $step++;
	  $row = fgets($in);
	  $row = trim($row);
	  //$row = safeText($row);
	  $status[$step] = $row;
	}
      fclose($in);
    }
  else
    echo("Fail to open data.status<br>");
  return($step);
}

//==========================================
function readAnyFile($serv,$file)
//==========================================
{
  global $content,$servuino;

  if($serv == 1)$file = $servuino.$file;
  $step = 0;
  $in = fopen($file,"r");
  if($in)
    {
      while (!feof($in))
	{
	  $row = fgets($in);
	  $row = trim($row);
	  $row = safeText($row);
	  $step++;
	  $content[$step] = $row;
	}
      fclose($in);
    }
  else
    echo("Fail to open $file<br>");
  return($step);
}

//==========================================
function formSelectFile($name,$fname,$file,$sel)
//==========================================
{
  $in = fopen($file,"r");
  if($in)
    {
      echo("$name<select name=\"$fname\">");
      while (!feof($in))
	{
	  $row = fgets($in);
	  $row = trim($row);
	  //$row = safeText($row);
	  if($row)
	    {
	      $selected = "";if($sel == $row)$selected = 'selected';
	      echo("<option value=\"$row\" $selected>$row</option>");
	    }
	}
      echo("</select>");
      fclose($in);
    }
  else
    echo("Fail to open $file <br>");
}

//==========================================
function readSketchInfo()
//==========================================
{
  global $curSketchName;
  $in = fopen('servuino/sketch.pde',"r");
  if($in)
    {
      while (!feof($in))
	{
	  $row = fgets($in);
	  $row = trim($row);
	  if(strstr($row,"SKETCH_NAME"))
	    {
	      //echo("$row");
	      if($pp = strstr($row,":"))
		{
		  sscanf($pp,"%s%s",$junk,$curSketchName);
		  //echo("[$curSketchName]");
		}
	    }
	}
      echo("</select>");
      fclose($in);
    }
  else
    echo("Fail to open $file <br>");
}

//==========================================
function safeText($text)
//==========================================
{
  $text = str_replace("#", "No.", $text); 
  $text = str_replace("$", "Dollar", $text); 
  $text = str_replace("%", "Percent", $text); 
  $text = str_replace("^", "", $text); 
  //$text = str_replace("&", "and", $text); 
  //$text = str_replace("*", "", $text); 
  //$text = str_replace("?", "", $text); 
  $text = str_replace("<", "R", $text); 
  //$text = str_replace(">", "T", $text); 
  $text = str_replace(" ", "&nbsp;", $text); 
  return($text);
}

//==========================================
function getExtension($str)
//==========================================
{
  $i = strrpos($str,".");
  if (!$i) { return ""; }
  $l = strlen($str) - $i;
  $ext = substr($str,$i+1,$l);
  return $ext;
}

//==========================================
function openDatabase($db,$user,$secret)
//==========================================
{
  if(DEBUG > 8)necho("[".DEBUG."]openDatabase($db,$user,$secret)<br>");
  if($db)
    {
      if($secret)
	{
	  if (!$link = mysql_connect("localhost", "$user", "$secret",true)) 
	    { exit("Could not connect to the MySQL server"); }
	}
      else
	{
	  if (!$link = mysql_connect("localhost", "$user",'',true)) 
	    { exit("Could not connect to the MySQL server"); }
	}

      if (!mysql_select_db($db,$link))  
	{ warning("Could not connect to Database"); sql($link,"CREATE DATABASE `proj3`"); }
      //necho("open $db $link<br>");
      return($link);
    }
  else
    {
      echo("Error openDatabase($db,$secret)<br>");
      return(0);
    }
}

//==========================================
function closeDatabase($link)
//==========================================
{
  if(DEBUG > 0)necho("[".DEBUG."]closeDatabase($link)<br>");
  mysql_close($link);
}

//==========================================
function showFile($title,$file)
//==========================================
{
  //echo("===== $title ======<br>");
  $in = fopen($file,"r");
  if($in)
    {
      while (!feof($in)) 
	{
	  $row = fgets($in);
	  echo($row);
	  echo("<br>");
	}
      fclose($in);
    }
  else
    echo("Fail to open $file<br>");
  return;
}

//==========================================
function uploadFile()
//==========================================
{

  define ("MAX_SIZE","300");
  $errors=0;
  $newname = '';

  if(isset($_POST['submit_file']))
    {

      $import=$_FILES['import_file']['name'];
      if ($import)
        {
          $file_name = stripslashes($_FILES['import_file']['name']);
          $file_name = safeText($file_name);
          $extension = getExtension($file_name);
          $extension = strtolower($extension);
          if (($extension != "txt") && ($extension != "pde") && ($extension != "c"))
            {
              echo "<h1>Unknown Import file Extension: $extension</h1>";
              $errors=1;
            }
          else
            {
              $size=filesize($_FILES['import_file']['tmp_name']);
              if ($size > MAX_SIZE*1024)
                {
                  echo "<h1>You have exceeded the size limit! $size</h1>";
                  $errors=1;
                }
              //$image_name=time().'.'.$extension;
              //$file_name = $db.'-'.$id.'.'.$extension;
              $newname="upload/".$file_name;
              $copied = move_uploaded_file($_FILES['import_file']['tmp_name'], $newname);
              if (!$copied)
                {
                  echo "<h1>Import Copy unsuccessfull! $size</h1>";
                  $errors=1;
                }
            }
        }
    }
  if(isset($_POST['submit_file']) && !$errors)
    {
      chmod($newname,0666);
      return($newname);
      // echo "<h1>File Uploaded Successfully! $size</h1>";
    }
  return($newname);
}
// End of File
?>
