<?
session_start();

define('T_UPLOAD_SKETCH','Upload Sketch');
define('T_CONFIG','Configuration');
define('T_RUN_TARGET','Run to');

$curSimLen = $_SESSION['cur_sim_len'];
$curSketch = $_SESSION['cur_sketch'];
$curStep   = $_SESSION['cur_step'];

$file = $servuino.'data.si';
readSimSi($file);

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
   $file = $servuino.'data.si';
   readSimSi($file);
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
  $digX[$ii] = $xx;
  $digY[$ii] = $yy;
  $pinModeD[$ii] = 2;
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
           if($pinModeD[$ii]==0)print("ctx.fillStyle = \"#000000\";");
           if($pinModeD[$ii]==1)print("ctx.fillStyle = \"#FFFF00\";");
           if($pinModeD[$ii]==2)print("ctx.fillStyle = \"#FFFFFF\";");
           if($pinModeD[$ii]==3)print("ctx.fillStyle = \"#FF0000\";");
           print("ctx.beginPath();");
           print("ctx.arc($digY[$ii], $digX[$ii], 5, 0, Math.PI*2, true);");
           print("ctx.rect($digY[$ii]-4, $digX[$ii]-12,8, 5);");
           print("ctx.closePath();");
           print("ctx.fill();");
          }
          for($ii=0; $ii<6; $ii++)
          {
           if($pinModeA[$ii]==0)print("ctx.fillStyle = \"#000000\";");
           if($pinModeA[$ii]==1)print("ctx.fillStyle = \"#FFFF00\";");
           if($pinModeA[$ii]==2)print("ctx.fillStyle = \"#FFFFFF\";");
           if($pinModeA[$ii]==3)print("ctx.fillStyle = \"#FF0000\";");
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
print("<body onload=\"draw();\">");
?>
 <div id="main" style=" background:grey; float:left; width:100%">
    <div id="left" style=" background:white; float:left; width:50%">
    <div id="above" style=" background:white; float:left; width:100%">

<?     print("<canvas id=\"tutorial\" width=\"$wBoard\" height=\"$hBoard\">"); ?>
     </canvas>
    </div> 
    <div id="below" style=" background:white; float:left; width:100%">
<?     print("<a href=\"index.php\"><img src=\"arduino_uno.jpg\" height=\"$hBoard\" width=\"$wBoard\" style=\"border: none;\" alt=\"Shapes\" ismap=\"ismap\"></a>"); ?>
    </div>
 >

<?
//echo("<div id=\"xmain\"  style=\"background-color:#009900\">");
echo("<div id=\"right\"  style=\"background-color:brown; float:left; width:50%\">");

// Command start
echo("<div id=command style=\" background:blue; float:left; width:100%\">");

if($doUpload == 1)
{
echo("<form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">");
echo("<input type=\"file\" name=\"import_file\" value=\"\">");
echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">");
echo("</form>");
}

if($doConfig == 1)
{
echo("<form name=\"configuration\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"set_configuration\">");
echo("Simulation Length<input type=\"text\" name=\"sim_len\" value=\"$curSimLen\"><br>");
system("ls upload > list.txt");
formSelectFile("Sketch","sketch","list.txt");
echo("<br><input type =\"submit\" name=\"submit_file\" value=\"".T_CONFIG."\">");
echo("</form>");
}
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
echo("</div>"); // Right end

// Middle start
//echo("<div id=\"middle\"  style=\"background-color:brown; float:left; width:20%\">");
//echo("Sketch: $curSketch<br>");
//echo("Simulation Length: $curSimLen<br>");
//echo("Step: $curStep<br>");
//echo("Event: $simulation[$curStep]<br>");
//echo("</div>"); // Middle end

// Right start
echo("<div id=\"right\"  style=\"background-color:brown; float:left; width:30%\">");

echo("<div id=eventList style=\"float:right; border : solid 2px #ff0000; background : #000000; color : #ffffff; padding : 4px; width : 100%; height:500px; overflow : auto; \">");
runTarget($curSimLen);
echo("</div>"); // eventList

// Current start
//echo("<div id=\"current\"  style=\"background-color:brown; float:left; width:100%\">");
//echo("Event: $simulation[$curStep] $curStep<br>");
//echo("</div>"); // Current end

//echo("<canvas id=\"myDrawing\" width=\"200\" height=\"200\">");
//echo("<p>Your browser doesnt support canvas.</p>");
//echo("</canvas>");

echo("</div>"); // Right end

echo("<div id=error style=\" background:cornsilk; float:left; width:100%\">");
$file = $servuino.'g++.error';
showFile("g++ error",$file);
$file = $servuino.'exec.error';
showFile("Execution error",$file);
echo("</div>"); // Error window

echo("</div>"); // Main
?>
  </body>
</html>
