<?
$upload = 'upload/';
$servuino = 'servuino/';

$simulation = array();

function show($step)
{
  global $simulation;
  echo("$simulation[$step]<br>");
}

function runTarget($target)
{
  global $curStep;
  global $simulation;


//echo("<div style=\"border : solid 2px #ff0000; background : #000000; color : #ffffff; padding : 4px; width : 400px; height : 500px; overflow : auto; \">");

  for($ii=$curStep;$ii<=$target;$ii++)
  {
     //show($ii);
     echo("<a href=index.php>$simulation[$ii]</a><br />");
  }
  $curStep = $ii;
//echo("</div>");
}
function readSimulation($file)
{
  global $simulation;

  $step = 0;
  $in = fopen($file,"r");
  if($in)
  {
  while (!feof($in))
    {
      $row = fgets($in);
     $row = trim($row);
      if($row[0]=='+')
       {
          $step++;
          $simulation[$step] = $row;
          //echo("($step: $row) <-> $simulation[$step]<br>");
       }
    }
  //echo("Total steps: $step<br>");
  fclose($in);
  }
  else
     echo("Fail to open $file<br>");
}


function formSelectFile($name,$fname,$file)
{
  $in = fopen($file,"r");
  if($in)
  {
  echo("$name<select name=\"$fname\">");
  while (!feof($in))
    {
      $row = fgets($in);
      $row = trim($row);
      echo("<option value=\"$row\">$row</option>");
    }
    echo("</select>");
  fclose($in);
  }
  else
    echo("Fail to open $file <br>");
}

function safeText($text)
{
   $text = str_replace("#", "No.", $text); 
   $text = str_replace("$", "Dollar", $text); 
   $text = str_replace("%", "Percent", $text); 
   $text = str_replace("^", "", $text); 
   $text = str_replace("&", "and", $text); 
   $text = str_replace("*", "", $text); 
   $text = str_replace("?", "", $text); 
   return($text);
}
//=======================================
function getExtension($str)
//======================================= 
{
  $i = strrpos($str,".");
  if (!$i) { return ""; }
  $l = strlen($str) - $i;
  $ext = substr($str,$i+1,$l);
  return $ext;
}
//==============================
function openDatabase($db,$user,$secret)
//==============================
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
//==============================
function closeDatabase($link)
//==============================
{
  if(DEBUG > 0)necho("[".DEBUG."]closeDatabase($link)<br>");
  mysql_close($link);
}
//=======================================
function showFile($title,$file)
//=======================================
{
  echo("===== $title ======<br>");
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
//=======================================
function uploadFile()
//=======================================
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

?>
