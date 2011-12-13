<?
session_start();

define('T_UPLOAD_SKETCH','Upload Sketch');
define('T_CONFIG','Configuration');
define('T_RUN_TARGET','Run to');

$curSimLen = $_SESSION['cur_sim_len'];
$curSketch = $_SESSION['cur_sketch'];
$curStep   = $_SESSION['cur_step'];

$file = $servuino.'data.su';
readSimulation($file);

// Open database
//$pswd  = MYSQL_PSWD;
//$dbCon = openDatabase('proj3','root',$pswd);
// GET ==============================================

$action    = $_GET['ac'];

if($action == 'load')
{
   $file = $upload.$curSketch;
   copySketch($file);
   compileSketch();
}

if($action == 'run' && $curSimLen > 0)
{
   execSketch($curSimLen,0);
   $file = $servuino.'data.su';
   readSimulation($file);
}

if($action == 'step')
{
   $target = $_GET['x'];
   if($target == 0) $curStep++;
}

if($action == 'reset')
{
   $curStep = 0;
}

//==================================================
function copySketch($sketch)
{
  global $upload;
  if (!copy($sketch,"servuino/sketch.pde")) {
    echo "failed to copy ($sketch)...<br>";
}
}

function compileSketch()
{
  system("cd servuino;g++ -o servuino servuino.c > g++.error 2>&1;");
}

function execSketch($steps,$source)
{
 system("cd servuino;./servuino $steps $source >exec.error 2>&1;");
}
// POST =============================================
if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $action = $_POST['action'];
    
    if($action == 'all_sketch' )
      {
	$fil = uploadFile();
        copySketch($fil);
        compileSketch();
        execSketch($curSimLen,0);
      }
    if($action == 'upload_sketch' )
       {
         $fil = uploadFile();
       }

    if($action == 'set_configuration' )
      {
        $curSimLen = $_POST['sim_len'];
        $curSketch = $_POST['sketch'];
      }

    if($action == 'run_target' )
      {
        $targetStep = $_POST['target_step'];
        runTarget($targetStep);
      }


  }

        $_SESSION['cur_step'] = $curStep;
        $_SESSION['cur_sim_len'] = $curSimLen;
        $_SESSION['cur_sketch'] = $curSketch;

//====================================================================
// Presentation 
//====================================================================
echo("<div id=\"main\"  style=\"background-color:#009900\">");
echo("<div id=\"left\"  style=\"background-color:brown; float:left; width:50%\">");

// Status start
echo("<div id=status style=\" background:grey; float:left; width:100%\">");
//echo("<img src=\"arduino_uno.jpg\" height=\"300\">");
echo("<a href=\"index.php\"><img src=\"arduino_uno.jpg\" height=\"300\" style=\"border: none;\" alt=\"Shapes\" ismap=\"ismap\"/></a>");
echo("</div>"); // Status end

// Command start
echo("<div id=command style=\" background:blue; float:left; width:100%\">");


echo("<form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">");
echo("<input type=\"file\" name=\"import_file\" value=\"\">"); 
echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">");
echo("</form>");

echo("<form name=\"configuration\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"set_configuration\">");
echo("Simulation Length<input type=\"text\" name=\"sim_len\" value=\"$curSimLen\"><br>");
system("ls upload > list.txt");
formSelectFile("Sketch","sketch","list.txt");
echo("<br><input type =\"submit\" name=\"submit_file\" value=\"".T_CONFIG."\">");
echo("</form>");

echo("<form name=\"target\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"run_target\">");
echo("<input type=\"text\" name=\"target_step\" value=\"$targetStep\"><br>");
echo("<br><input type =\"submit\" name=\"submit_file\" value=\"".T_RUN_TARGET."\">");
echo("</form>");

echo("<a href=index.php?ac=load>Load</a><br>");
echo("<a href=index.php?ac=run>Run</a><br>");
echo("<a href=index.php?ac=step&x=0>step</a><br>");
echo("<a href=index.php?ac=reset>reset</a><br>");

echo("</div>"); // Command end
echo("</div>"); // Left end

// Middle start
echo("<div id=\"middle\"  style=\"background-color:brown; float:left; width:20%\">");
echo("Sketch: $curSketch<br>");
echo("Simulation Length: $curSimLen<br>");
echo("Step: $curStep<br>");
echo("Event: $simulation[$curStep]<br>");
echo("</div>"); // Middle end

// Right start
echo("<div id=\"right\"  style=\"background-color:brown; float:left; width:30%\">");

echo("<div id=eventList style=\"float:right; border : solid 2px #ff0000; background : #000000; color : #ffffff; padding : 4px; width : 100%; height:500px; overflow : auto; \">");
runTarget($curSimLen);
echo("</div>"); // eventList

// Current start
echo("<div id=\"current\"  style=\"background-color:brown; float:left; width:100%\">");
echo("Event: $simulation[$curStep] $curStep<br>");
echo("</div>"); // Current end

echo("</div>"); // Right end

echo("<div id=error style=\" background:cornsilk; float:left; width:100%\">");
$file = $servuino.'g++.error';
showFile("g++ error",$file);
$file = $servuino.'exec.error';
showFile("Execution error",$file);
echo("</div>"); // Error window

echo("</div>"); // Main
?>
