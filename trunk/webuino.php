<?
session_start();


define('T_UPLOAD_SKETCH','Upload Sketch');
define('T_CONFIG','Configuration');
define('T_LOOP_F','Next Loop');
define('T_LOOP_B','Prev Loop');
define('T_STEP_F','Next Step');
define('T_STEP_B','Prev Step');

define('BLACK', '0');
define('YELLOW','1');
define('WHITE', '2');
define('RED',   '3');
define('GREEN', '4');

$curSimLen = $_SESSION['cur_sim_len'];
$curSketch = $_SESSION['cur_sketch'];
$curStep   = $_SESSION['cur_step'];
$curLoop   = $_SESSION['cur_loop'];
$curLog    = $_SESSION['cur_log'];

init($curSimLen);
readSimulation('data.arduino');
readStatus();
$logLen = readAnyFile($curLog);

// GET ==============================================
$input  = array_keys($_GET); 
$coords = explode(',', $input[0]); 
//print("X coordinate : ".$coords[0]."<br> Y Coordinate : ".$coords[1]); 

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
    //$file = $servuino.'data.custom';
    //readSimSi($file);
  }

if($action == 'step')
  {
    $target = $_GET['x'];
    if($target == 0) $curStep++;
  }

if($action == 'reset')
  {
    $curStep = 1;
  }

if($action == 'log')
  {
    $source = $_GET['x'];
    if($source == 'code')   $curLog = 'data.code';
    if($source == 'error')  $curLog = 'data.error';
    if($source == 'custom') $curLog = 'data.custom';
    if($source == 'arduino')$curLog = 'data.arduino';
    if($source == 'scen')   $curLog = 'data.scen';
    if($source == 'status') $curLog = 'data.status';
    $_SESSION['cur_log'] = $curLog;
    $logLen = readAnyFile($curLog);
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

$_SESSION['cur_step']    = $curStep;
$_SESSION['cur_sim_len'] = $curSimLen;
$_SESSION['cur_sketch']  = $curSketch;
$_SESSION['cur_log']     = $curLog;



//====================================================================
// Presentation 
//====================================================================
$hBoard = 300; // 300
$wBoard = 500; // 500
//$input  = array_keys($_GET);
//$coords = explode(',', $input[0]);
$bb = $coords[0]; $aa=$coords[1];

// Digital Pin Positions
$yy = 220;
for($ii=0; $ii<14; $ii++)
  {
    $xx = 17; $yy = $yy+17;
    if($ii == 6) $yy = $yy+10;
    $digX[13-$ii] = $xx;
    $digY[13-$ii] = $yy;
    $pinModeD[$ii] = 0;
  }

// Analog Pin Positions
$yy = 363;
for($ii=0; $ii<6; $ii++)
  {
    $xx = 288; $yy = $yy+17;
    $anaX[$ii] = $xx;
    $anaY[$ii] = $yy;
    $pinModeA[$ii] = 0;
  }

// Step + - positions
$stepForwardY  = 312;
$stepForwardX  = 80;
$stepBackwardY = 273;
$stepBackwardX = 80;

$resetY = 410;
$resetX = 149;

$uploadY = 434;
$uploadX = 212;

$TXledY = 229;
$TXledX = 92;

$RXledY = 229;
$RXledX = 106;

$onOffY = 427;
$onOffX = 91;

$sketchNameY = 280;
$sketchNameX = 220;

$configY = 360;
$configX = 78;

$sens = 5;


//===========================================
for($ii=0; $ii<14; $ii++)
  {
    $xx = $digX[$ii];
    $yy = $digY[$ii];
    if($aa > $xx-$sens && $aa < $xx+$sens && $bb > $yy-$sens && $bb < $yy+$sens)
      $pinModeD[$ii] = 1;
  }
for($ii=0; $ii<6; $ii++)
  {
    $xx = $anaX[$ii];
    $yy = $anaY[$ii];
    if($aa > $xx-$sens && $aa < $xx+$sens && $bb > $yy-$sens && $bb < $yy+$sens)
      $pinModeA[$ii] = 3;
  }

if($aa > $stepForwardX-$sens && $aa < $stepForwardX+$sens && $bb > $stepForwardY-$sens && $bb < $stepForwardY+$sens)
  {
    $curStep++;
    $_SESSION['cur_step'] = $curStep;
    $pinModeD[1] = 3;
  }
if($aa > $stepBackwardX-$sens && $aa < $stepBackwardX+$sens && $bb > $stepBackwardY-$sens && $bb < $stepBackwardY+$sens)
  {
    $curStep--;
    $_SESSION['cur_step'] = $curStep;
    $pinModeD[1] = 1;
  }

if($aa > $resetX-$sens && $aa < $resetX+$sens && $bb > $resetY-$sens && $bb < $resetY+$sens)
  {
    $curStep = 0;
    $_SESSION['cur_step'] = $curStep;
  }

$doUpload = 0;
if($aa > $uploadX-$sens && $aa < $uploadX+$sens && $bb > $uploadY-$sens && $bb < $uploadY+$sens)
  {
    $doUpload = 1;
  }

$doConfig = 0;
if($aa > $configX-$sens && $aa < $configX+$sens && $bb > $configY-$sens && $bb < $configY+$sens)
  {
    $doConfig = 1;
  }
 
decodeStatus($status[$curStep]);
//===========================================
?>
<html>
<head>
<title>Canvas tutorial</title>
<script type="text/javascript">

  function pause(milliseconds) {
  var dt = new Date();
  while ((new Date()) - dt <= milliseconds) { /* Do nothing */ }
}

function draw(){
  var canvas = document.getElementById('tutorial');
  if (canvas.getContext){
    var ctx = canvas.getContext('2d');
    var imageObj = new Image();
    imageObj.src = "arduino_uno.jpg";
    ctx.drawImage(imageObj, 0, 0,500,300);

    <?
      // On OFF led
      print("ctx.fillStyle = \"#FFFF00\";");
      print("ctx.beginPath();");
      print("ctx.rect($onOffY, $onOffX,8, 5);");
      //print("ctx.arc($onOffY, $onOffX, 4, 0, Math.PI*2, true);");
      print("ctx.closePath();");
      print("ctx.fill();");

      for($ii=0; $ii<14; $ii++)
	{
	  if($pinModeD[$ii]==0)print("ctx.fillStyle = \"#000000\";");// black
	  if($pinModeD[$ii]==1)print("ctx.fillStyle = \"#FFFF00\";");// yellow
	  if($pinModeD[$ii]==2)print("ctx.fillStyle = \"#FFFFFF\";");// white
	  if($pinModeD[$ii]==3)print("ctx.fillStyle = \"#FF0000\";");// red
	  if($pinModeD[$ii]==4)print("ctx.fillStyle = \"#00FF00\";");// green
	  print("ctx.beginPath();");
	  //print("ctx.arc($digY[$ii], $digX[$ii], 5, 0, Math.PI*2, true);");
	  print("ctx.rect($digY[$ii]-4, $digX[$ii]-12,8, 5);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}
      for($ii=0; $ii<14; $ii++)
	{
	  if($pinStatusD[$ii]==0)print("ctx.fillStyle = \"#000000\";");// black
	  if($pinStatusD[$ii]==1)print("ctx.fillStyle = \"#FFFF00\";");// yellow
	  if($pinStatusD[$ii]==2)print("ctx.fillStyle = \"#FFFFFF\";");// white
	  if($pinStatusD[$ii]==3)print("ctx.fillStyle = \"#FF0000\";");// red
	  if($pinStatusD[$ii]==4)print("ctx.fillStyle = \"#00FF00\";");// green
	  print("ctx.beginPath();");
	  print("ctx.arc($digY[$ii], $digX[$ii], 5, 0, Math.PI*2, true);");
	  //print("ctx.rect($digY[$ii]-4, $digX[$ii]-12,8, 5);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}
      for($ii=0; $ii<6; $ii++)
	{
	  if($pinStatusA[$ii]==0)print("ctx.fillStyle = \"#000000\";");// black
	  if($pinStatusA[$ii]==1)print("ctx.fillStyle = \"#FFFF00\";");// yellow
	  if($pinStatusA[$ii]==2)print("ctx.fillStyle = \"#FFFFFF\";");// white
	  if($pinStatusA[$ii]==3)print("ctx.fillStyle = \"#FF0000\";");// red
	  if($pinStatusA[$ii]==4)print("ctx.fillStyle = \"#00FF00\";");// green
	  print("ctx.beginPath();");
	  print("ctx.arc($anaY[$ii], $anaX[$ii], 5, 0, Math.PI*2, true);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}

      print("ctx.font = \"15pt Calibri\";");
      print("ctx.fillStyle = \"#FFFFFF\";");
      print("ctx.fillText(\"Heater Control\",$sketchNameY,$sketchNameX);");


      ?>
        }
}
</script>
<style type="text/css">
  canvas { border: 0px solid black; }
</style>
</head>
<?
//==================================================================
print("<body onload=\"draw();\" link=\"black\" alink=\"black\" vlink=\"black\">\n");
?>
<div id="main" style=" background:white; float:left; width:100%">
  <div id="left" style=" background:white; float:left; width:100%; height: 30px;">
<?
echo("<a href=index.php?ac=load>Load</a>\n");
echo("<a href=index.php?ac=run>Run</a>\n");
echo("<a href=index.php?ac=step&x=0>step</a>\n");
echo("<a href=index.php?ac=reset>reset</a>\n");

echo("<a href=index.php?ac=sketch>Sketch</a>\n");

echo("<a href=index.php?ac=log&x=status>Status</a>\n");
echo("<a href=index.php?ac=log&x=error>Error</a>\n");
echo("<a href=index.php?ac=log&x=code>Code</a>\n");
echo("<a href=index.php?ac=log&x=scen>Scen</a>\n");
echo("<a href=index.php?ac=log&x=arduino>Arduino</a>\n");
echo("<a href=index.php?ac=log&x=custom>Custom</a>\n");
?>
  </div>
  <div id="left" style=" background:white; float:left; width:50%">
    <div id="above" style=" background:white; float:left; width:100%">
       <? print("<canvas id=\"tutorial\" width=\"$wBoard\" height=\"$hBoard\"></canvas>\n"); ?>
    </div>
 
    <div id="below" style=" background:white; float:left; width:100%">
  <?     print("<a href=\"index.php\"><img src=\"arduino_uno.jpg\" height=\"$hBoard\" width=\"$wBoard\" style=\"border: none;\" alt=\"Shapes\" ismap=\"ismap\"></a>\n"); ?>
    </div>

<?
  //echo("<div id=\"xmain\"  style=\"background-color:#009900\">");
echo("</div><div id=\"right\"  style=\"background-color:white; float:left; width:50%\">\n");
// Command start
echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%\">\n");
print("Sketch: $curSketch Step: $curStep Loop: $curLoop  Simulation length: $curSimLen");

// Log window
echo("<form name=\"f_sel_win\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
echo("<input type=\"hidden\" name=\"action\" value=\"select_window2\">\n");
echo("$name<select name=\"$fname\">");
echo("<option value=\"log\">Log</option>");
echo("<option value=\"serial\">Serial Output</option>");
echo("<option value=\"status\">Status</option>");
echo("<option value=\"error\">Error</option>");
echo("<option value=\"status\">Status</option>");
echo("<option value=\"error\">Error</option>");
echo("</select>");
echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_SEL_WIN."\">\n");
echo("</form>");
echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #ffffff; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
showAnyFile($logLen);
echo("</div>\n");

// Serial Output window

echo("<div id=\"serLis\"t style=\"float:right; border : solid 1px #000000; background : #ffffff; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
showSimulation($curStep);
echo("</div>\n"); 

// Command start
echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%\">\n");

if($doUpload == 1)
  {
    echo("<form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
    echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">\n");
    echo("<input type=\"file\" name=\"import_file\" value=\"\">\n");
    echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">\n");
    echo("</form>");
  }

if($doConfig == 1)
  {
    echo("<form name=\"configuration\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
    echo("<input type=\"hidden\" name=\"action\" value=\"set_configuration\">\n");
    echo("Simulation Length<input type=\"text\" name=\"sim_len\" value=\"$curSimLen\"><br>\n");
    system("ls upload > list.txt");
    formSelectFile("Sketch","sketch","list.txt");
    echo("<br><input type =\"submit\" name=\"submit_file\" value=\"".T_CONFIG."\">\n");
    echo("</form>\n");
  }

$xx  = $curStep-1;
echo("  Step <a href=index.php?ac=step&x=$xx> - </a>\n");
$xx  = $curStep+1;
echo("<a href=index.php?ac=step&x=$xx> + </a>\n");

$xx  = $curLoop-1;
echo("  Loop <a href=index.php?ac=step&x=$xx> - </a>\n");
$xx  = $curLoop+1;
echo("<a href=index.php?ac=step&x=$xx> + </a>\n");


   echo("</div>\n"); // Command end

echo("</div>\n"); // Right end



echo("<div id=error style=\" background:yellow; float:left; width:100%\">\n");
$file = $servuino.'g++.error';
showAnyFile($file);
$file = $servuino.'exec.error';
showAnyFile($file);
$file = $servuino.'data.error';
showAnyFile($file);
echo("</div>\n"); // Error window

echo("</div>\n"); // Main
?>

