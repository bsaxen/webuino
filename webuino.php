<?
$menu = 'logging';
$ready = "";

$curSimLen = $_SESSION['cur_sim_len'];
$curSketch = $_SESSION['cur_sketch'];
$curStep   = $_SESSION['cur_step'];
$curLoop   = $_SESSION['cur_loop'];
$curLog    = $_SESSION['cur_log'];
$curMenu   = $_SESSION['cur_menu'];
$curFile   = $_SESSION['cur_file'];
$curSketchName = $_SESSION['cur_sketch_name'];

if(!$curMenu)$curMenu = 'logging';

init($curSimLen);
readSketchInfo();
readSimulation('data.custom');
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
    execSketch($curSimLen,0);
  }

if($action == 'menu')
  {
    $curMenu = $_GET['x'];
    $_SESSION['cur_menu'] = $curMenu; 
  }


if($action == 'run' && $curSimLen > 0)
  {
    execSketch($curSimLen,0);
    //$file = $servuino.'data.custom';
    //readSimSi($file);
  }

if($action == 'step')
  {
    $curStep = $_GET['x'];
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


// POST =============================================
if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $action = $_POST['action'];
    
    if($action == 'select_file' )
      {
	$curFile = $_POST['file'];
      }
    if($action == 'upload_sketch' )
      {
	$fil = uploadFile();
      }

    if($action == 'set_configuration' )
      {
        $curSimLen = $_POST['sim_len'];
        $curSketch = $_POST['sketch'];
        copySketch($curSketch);
        compileSketch();
        execSketch($curSimLen,0);
        $curStep = 0;
	init($curSimLen);
	readSketchInfo();
	readSimulation('data.custom');
	readStatus();
        $ready = "Sketch loaded!";
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
$_SESSION['cur_menu']    = $curMenu; 
$_SESSION['cur_file']    = $curFile; 
$_SESSION['cur_sketch_name'] = $curSketchName;

//====================================================================
// Calulate positions in image
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

// Check what action is pointed at
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

// Decode current status 
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
      print("ctx.fillText(\"$curSketchName\",$sketchNameY,$sketchNameX);");


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
<div id="main" style=" background:white; float:left; width:100%;">
  <div id="left" style=" background:white; float:left; width:100%; height: 40px;">
  <?
 //echo("<a href=index.php?ac=load>Load</a>\n");
 //echo("<a href=index.php?ac=run>Run</a>\n");
echo("<div style=\"text-align:right;font-size:20px; background:white; float:left; width:100%;\">");
echo("<a href=index.php?ac=menu&x=logging> Log </a>\n");
echo("<a href=index.php?ac=menu&x=config> Load </a>\n");
echo("<a href=index.php?ac=menu&x=file> Files </a>\n");
echo("</div>");
echo("<div style=\"text-align:center;font-size:15px; background:white; float:left; width:100%;\">");
echo("Loaded Sketch: $curSketch Now: $curStep   Simulation Length: $curSimLen steps");
echo("</div>");
?>
</div>
<div id="left" style=" background:white; float:left; width:50%">
  <div id="above" style=" background:white; float:left; width:100%;">
  <? print("<canvas id=\"tutorial\" width=\"$wBoard\" height=\"$hBoard\"></canvas>\n"); ?>
</div>
 
<div id="below" style=" background:white; float:left; width:100%;">
  <?     print("<a href=\"index.php\"><img src=\"arduino_uno.jpg\" height=\"$hBoard\" width=\"$wBoard\" style=\"border: none;\" alt=\"Shapes\" ismap=\"ismap\"></a>\n"); ?>
</div>

<?
  //================================================================

echo("</div><div id=\"right\"  style=\"background-color:white; float:left; width:50%;\">\n");
if($curMenu == 'logging')
  {
    // Command start
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");


    // Log window

    echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #ffffff; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
    showStep($curStep);
    echo("</div>\n");

    // Serial Output window

    echo("<div id=\"serLis\"t style=\"float:right; border : solid 1px #000000; background : #ffffff; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
    showSimulation($curStep);
    echo("</div>\n"); 


 

  }
//========================================================================
 else if ($curMenu == 'config')
   {
    // Command start
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");


	echo("<br><br><form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">\n");
	echo("<input type=\"file\" name=\"import_file\" value=\"\">\n");
	echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">\n");
	echo("</form><br><br>");
     

	echo("<form name=\"configuration\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"set_configuration\">\n");
	echo("Simulation Length <input type=\"text\" name=\"sim_len\" value=\"$curSimLen\" size=\"5\"><br>\n");
	system("ls upload > list.txt");
	formSelectFile("Sketch Library","sketch","list.txt",$curSketch);
	echo("<br><input type =\"submit\" name=\"submit_file\" value=\"".T_SELECT."\"><br>\n");
	echo("</form>\n");
	if($ready)echo("$ready");
    
    echo("</div>\n"); // Command end
   }

 else if ($curMenu == 'file')
   {
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");

    echo("<form name=\"f_sel_win\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
    echo("<input type=\"hidden\" name=\"action\" value=\"select_file\">\n");
    echo("<select name=\"file\">");
    
    $selected = "";$temp = 'data.custom';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Custom Log</option>");
    $selected = "";$temp = 'data.arduino';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"  $selected>Arduino Log</option>");
    $selected = "";$temp = 'data.status';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Status Log</option>");
    $selected = "";$temp = 'data.code';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Code Log</option>");
    $selected = "";$temp = 'data.error';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Error Log</option>");
    $selected = "";$temp = 'sketch.pde';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Sketch</option>");
    $selected = "";$temp = 'data.scen';if($curFile == $temp)$selected = 'selected';
    echo("<option value=\"$temp\"   $selected>Scenario</option>");
    echo("</select>");
    echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_SELECT."\">\n");
    echo("</form>");

    echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #ffffff; color : #000000; padding : 4px; width : 98%; height:550px; overflow : auto; \">\n");
    $len = readAnyFile($curFile);
    showAnyFile($len);
    echo("</div>\n");

    echo("</div>\n"); // Command end
   }

echo("</div>\n"); // Right end

echo("<div id=error style=\" background:yellow; float:left; width:100%;\">\n");
$file = $servuino.'g++.error';
showAnyFile($file);
$file = $servuino.'exec.error';
showAnyFile($file);
$file = $servuino.'data.error';
showAnyFile($file);
echo("</div>\n"); // Error window

echo("</div>\n"); // Main
?>

