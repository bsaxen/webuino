<?
$ready = "";

$curSimLen = $_SESSION['cur_sim_len'];
$curSketch = $_SESSION['cur_sketch'];
$curStep   = $_SESSION['cur_step'];
$curLoop   = $_SESSION['cur_loop'];
$curLog    = $_SESSION['cur_log'];
$curMenu   = $_SESSION['cur_menu'];
$curFile   = $_SESSION['cur_file'];
$curSketchName = $_SESSION['cur_sketch_name'];

if(!$curMenu)$curMenu = 'logA';

init($curSimLen);
readSketchInfo();
readSimulation('data.custom');
readSerial('data.serial');
readStatus();


$logLen = readAnyFile(1,$curLog);

// GET ==============================================
$input  = array_keys($_GET); 
$coords = explode(',', $input[0]); 
//print("X coordinate : ".$coords[0]."<br> Y Coordinate : ".$coords[1]); 

$action    = $_GET['ac'];

if($action == 'load')
  {
    $alt = $_GET['x'];
    if($alt == 'CGE')
      {
	$file = $upload.$curSketch;
	copySketch($file);// C
	compileSketch(); // G
	execSketch($curSimLen,0); // E
      }
    if($alt == 'GE')
      {
	compileSketch(); // G
	execSketch($curSimLen,0); // E
      }
    if($alt == 'E')
      {
	execSketch($curSimLen,0); // E
      }

    $curStep = 0;
    init($curSimLen);
    readSketchInfo();
    readSimulation('data.custom');
    readSerial('data.serial');
    readStatus();
    $ready = "Sketch loaded!";
  }

if($action == 'menu')
  {
    $curMenu = $_GET['x'];
    $_SESSION['cur_menu'] = $curMenu; 
  }


if($action == 'run' && $curSimLen > 0)
  {
    execSketch($curSimLen,0);
  }

if($action == 'step')
  {
    $curStep = $_GET['x'];
  }

if($action == 'edit_file')
  {
    $curEditFlag = 1;
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
    if($source == 'serial') $curLog = 'data.serial';
    $_SESSION['cur_log'] = $curLog;
    $logLen = readAnyFile(1,$curLog);
  }


// POST =============================================
if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $action = $_POST['action'];
    
    if($action == 'select_file' )
      {
	$curFile = $_POST['file'];
	$what = $_POST['submit_select'];
	if($what == T_EDIT) $curEditFlag = 1;
      }

    if($action == 'edit_file')
      {
	$tempFile = $_POST['file_name'];
	$data = $_POST['file_data'];
	$what = $_POST['submit_edit'];

	$fp = fopen($tempFile, 'w')or die("Could not open file $tempFile (write)!");;
	fwrite($fp,$data) or die("Could not write to file $tempFile !");
	fclose($fp);

	if($what == T_LOAD)
	  {
	    compileSketch();
	    execSketch($curSimLen,0);
	    $curStep = 0;
	    init($curSimLen);
	    readSketchInfo();
	    readSimulation('data.custom');
	    readStatus();
	    readSerial('data.serial');
	    $ready = "Sketch loaded!";
	  }
	if($what == T_RUN)
	  {
	    execSketch($curSimLen,1);
	    $curStep = 0;
	    init($curSimLen);
	    readSketchInfo();
	    readSimulation('data.custom');
	    readStatus();
	    readSerial('data.serial');
	    $ready = "Sketch Executed!";
	  }


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
	readSerial('data.serial');
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

$TXledY = 230;
$TXledX = 103;

$RXledY = 230;
$RXledX = 106;

$led13Y = 230;
$led13X =  72;

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
      $black  = "ctx.fillStyle = \"#000000\";";
      $yellow = "ctx.fillStyle = \"#FFFF00\";";
      $white  = "ctx.fillStyle = \"#FFFFFF\";";
      $red    = "ctx.fillStyle = \"#FF0000\";";
      $green  = "ctx.fillStyle = \"#00FF00\";";
      $blue   = "ctx.fillStyle = \"#0000FF\";";
      $fuchsia= "ctx.fillStyle = \"#FF00FF\";";
      $aqua   = "ctx.fillStyle = \"#00FFFF\";";
      

      // On OFF led
      print("ctx.fillStyle = \"#FFFF00\";");
      print("ctx.beginPath();");
      print("ctx.rect($onOffY, $onOffX,8, 5);");
      //print("ctx.arc($onOffY, $onOffX, 4, 0, Math.PI*2, true);");
      print("ctx.closePath();");
      print("ctx.fill();");

      // TX led when Serial Output
      if(strlen($serial[$curStep]))
	{
	  print("ctx.fillStyle = \"#FFFF00\";");
	  print("ctx.beginPath();");
	  print("ctx.rect($TXledY-4, $TXledX-12,8, 5);");
	  //print("ctx.arc($onOffY, $onOffX, 4, 0, Math.PI*2, true);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}

      // Digital Pins Mode
      for($ii=0; $ii<14; $ii++)
	{
	  if($pinModeD[$ii]==0)print($black);
	  if($pinModeD[$ii]==1)print($yellow);
	  if($pinModeD[$ii]==2)print($white);
	  if($pinModeD[$ii]==3)print($red);
	  if($pinModeD[$ii]==4)print($green);
	  if($pinModeD[$ii]==5)print($blue);
	  if($pinModeD[$ii]==6)print($fuchsia);
	  if($pinModeD[$ii]==7)print($aqua);
	  print("ctx.beginPath();");
	  //print("ctx.arc($digY[$ii], $digX[$ii], 5, 0, Math.PI*2, true);");
	  print("ctx.rect($digY[$ii]-4, $digX[$ii]-12,8, 5);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}

      // Digital Pins Status
      for($ii=0; $ii<14; $ii++)
	{
	  if($pinStatusD[$ii]==0)print($black);
	  if($pinStatusD[$ii]==1)print($yellow);
	  if($pinStatusD[$ii]==2)print($white);
	  if($pinStatusD[$ii]==3)print($red);
	  if($pinStatusD[$ii]==4)print($green);
	  if($pinStatusD[$ii]==5)print($blue);
	  if($pinStatusD[$ii]==6)print($fuchsia);
	  if($pinStatusD[$ii]==7)print($aqua);
	  print("ctx.beginPath();");
	  print("ctx.arc($digY[$ii], $digX[$ii], 5, 0, Math.PI*2, true);");
	  if($ii == 13 && $pinStatusD[13]>0)
	    print("ctx.rect($led13Y-4, $led13X-12,8, 5);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}

      // Analog Pins Status
      for($ii=0; $ii<6; $ii++)
	{
	  if($pinStatusA[$ii]==0)print($black);
	  if($pinStatusA[$ii]==1)print($yellow);
	  if($pinStatusA[$ii]==2)print($white);
	  if($pinStatusA[$ii]==3)print($red);
	  if($pinStatusA[$ii]==4)print($green);
	  if($pinStatusA[$ii]==5)print($blue);
	  if($pinStatusA[$ii]==6)print($fuchsia);
	  if($pinStatusA[$ii]==7)print($aqua);
	  print("ctx.beginPath();");
	  print("ctx.arc($anaY[$ii], $anaX[$ii], 5, 0, Math.PI*2, true);");
	  print("ctx.closePath();");
	  print("ctx.fill();");
	}

      // Write Sketch Name on IC
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
echo("<body onload=\"draw();\" link=\"black\" alink=\"black\" vlink=\"black\">\n");
echo("<div id=\"main\" style=\" background:white; float:left; width:100%;\">");
echo("  <div id=\"left\" style=\" background:white; float:left; width:100%; height: 40px;\">");
echo("    <div style=\"text-align:center;font-size:20px; background:white; float:left; width:48%;\">");
echo("         <a href=index.php?ac=step&x=1>");
echo("         <img border=\"0\" src=\"reset.gif\" alt=\"Reset\" width=\"50\" height=\"32\"</a>\n");
$temp = $curStep - 1;
echo("         <a href=index.php?ac=step&x=$temp>");
echo("         <img border=\"0\" src=\"backward.gif\" alt=\"Backward\" width=\"50\" height=\"32\"</a>\n");
$temp = $curStep + 1;
echo("         <a href=index.php?ac=step&x=$temp>");
echo("         <img border=\"0\" src=\"forward.gif\" alt=\"Forward\" width=\"50\" height=\"32\"</a>\n");
echo("      <a href=index.php?ac=menu&x=logA>\n");
echo("         <img border=\"0\" src=\"logA.gif\" alt=\"LogA\" width=\"50\" height=\"32\"</a>\n");
echo("      <a href=index.php?ac=menu&x=logB>\n");
echo("         <img border=\"0\" src=\"logB.gif\" alt=\"LogB\" width=\"50\" height=\"32\"</a>\n");
echo("      <a href=index.php?ac=menu&x=config>");
echo("         <img border=\"0\" src=\"library.gif\" alt=\"Library\" width=\"50\" height=\"32\"</a>\n");
echo("      <a href=index.php?ac=menu&x=file>");
echo("         <img border=\"0\" src=\"data.gif\" alt=\"Data\" width=\"50\" height=\"32\"</a>\n");
echo("      <a href=index.php?ac=menu&x=help>");
echo("         <img border=\"0\" src=\"help.gif\" alt=\"Help\" width=\"50\" height=\"32\"</a>\n");
echo("    </div>");
echo("    <div style=\"text-align:center;font-size:17px; background:white; float:left; width:48%;\">");
echo("      Sketch: $curSketch <br>Current Step: $curStep ($curSimLen)");
echo("    </div>");

echo("  </div>");
echo("  <div id=\"left\" style=\" background:white; float:left; width:50%\">");
echo("    <div id=\"above\" style=\" background:white; float:left; width:100%;\">");
echo("      <canvas id=\"tutorial\" width=\"$wBoard\" height=\"$hBoard\"></canvas>\n");
echo("    </div>");

echo("    <div id=\"below\" style=\" background:white; float:left; width:100%;\">");
    // Serial window
echo("<div id=\"serWin\"t style=\"font-family: Courier,monospace;float:left; border : solid 2px #FF0000; background :#BDBDBD; color:#FF0000; padding : 4px; width : 97%; height:250px; overflow : auto; \">\n");
showSerial($curStep);
echo("</div>\n"); 


// // Arduino Board isMap---------------------------
// echo("    <div id=\"below\" style=\" background:white; float:left; width:100%;\">");

// echo("<a href=\"index.php\"><img src=\"arduino_uno.jpg\" height=\"$hBoard\" width=\"$wBoard\" style=\"border: none;\" alt=\"Shapes\" ismap=\"ismap\"></a>\n");
// echo("</div>\n"); 


// //-----------------------------------------------
 echo("    </div>");
echo("  </div>");

//================================================================
echo("<div id=\"right\"  style=\"background-color:white; float:left; width:50%;\">\n");
//================================================================
if($curMenu == 'logA')
  {
    // Command start
    echo("<div id=loga style=\" padding: 1px; background:white; float:left; width:100%;\">\n");

    // Log window
    echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #FFFFFF; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
    showStep($curStep);
    echo("</div>\n");

    // Simulation Output window
    echo("<div id=\"serLis\"t style=\"float:right; border : solid 1px #000000; background : #FFFFFF; color : #000000; padding : 4px; width : 48%; height:550px; overflow : auto; \">\n");
    showSimulation($curStep);
    echo("</div>\n"); 
    echo("</div>\n"); 
  }
else if($curMenu == 'logB')
  {
    // Command start
    echo("<div id=logb style=\" padding: 1px; background:white; float:left; width:100%;\">\n");

    // Log window
    echo("</div><div id=\"logWin\" style=\"float:left; border : solid 1px #C0C0C0; background : #FFFFFF; color : #000000; padding : 4px; width : 98%; height:293px; overflow : auto; \">\n");
    showStep($curStep);
    echo("</div>");

    // Simulation Output window
    echo("<div id=\"simWin\"t style=\"float:left; border : solid 1px #C0C0C0; background : #FFFFFF; color : #000000; padding : 4px; width : 98%; height:250px; overflow : auto; \">\n");
    showSimulation($curStep);
    echo("</div>\n"); 
    echo("</div>\n"); 
  }
//================================================================
 else if ($curMenu == 'config')
//================================================================
   {
    // Command start
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");

	echo("<hr><form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">\n");
	echo("<input type=\"file\" name=\"import_file\" value=\"\">\n");
	echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">\n");
	echo("</form><br<br>");
	echo("<hr>");
     	echo("<table border=\"0\"><tr><td>");
	echo("<form name=\"configuration\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"set_configuration\">\n");
	echo("Simulation Length <input type=\"text\" name=\"sim_len\" value=\"$curSimLen\" size=\"5\"></td>\n");
	system("ls upload > list.txt");
	echo("<td>");
	formSelectFile("Sketch Library","sketch","list.txt",$curSketch);
	echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_LOAD."\"></td>\n");
	echo("</form></tr>");
    	if($ready)echo("<tr><td>$ready</td></tr>");
	echo("</table><hr>");

	echo("Analog Pin Settings at step: $curStep<br>");
	echo("<table><tr>");
	echo("<form name=\"f_set_scenario\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"set_scenario\">\n");
	for($ii=0;$ii<6;$ii++)
	  {
	    echo("<td>Pin $ii<input type=\"text\" name=\"pin_$ii\" value=\"$pinValueA[$ii]\" size=\"3\"></td>");
	  }
	echo("<td><input type =\"submit\" name=\"submit_scenario\" value=\"".T_LOAD."\"></td>\n");
	echo("</tr></form>");
	echo("</table><hr>");
    echo("</div>\n");
   }
//================================================================
 else if ($curMenu == 'help')
//================================================================
   {
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");
    $len = readAnyFile(0,'help.txt');
    showAnyFile($len);
    echo("</div>\n");
   }

//================================================================
 else if ($curMenu == 'file')
//================================================================
   {
    echo("<div id=command style=\" padding: 5px; background:white; float:left; width:100%;\">\n");

    if($curEditFlag == 0)
      {
	echo("<table><tr><td>");
	echo("<form name=\"f_sel_win\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"select_file\">\n");
	echo("<select name=\"file\">");
	$selected = "";$temp = 'data.custom';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Custom Log</option>");
	$selected = "";$temp = 'data.arduino';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"  $selected>Arduino Log</option>");
	$selected = "";$temp = 'data.status';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Status Log</option>");
	$selected = "";$temp = 'data.serial';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Serial Log</option>");
	$selected = "";$temp = 'data.code';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Code Log</option>");
	$selected = "";$temp = 'data.error';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Error Log</option>");
	$selected = "";$temp = 'sketch.pde';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Sketch</option>");
	$selected = "";$temp = 'data.scen';if($curFile == $temp)$selected = 'selected';
	echo("<option value=\"$temp\"   $selected>Scenario</option>");
	echo("</select>");
	echo("<input type =\"submit\" name=\"submit_select\" value=\"".T_SELECT."\">\n");
	if($curFile == 'sketch.pde')echo("<input type =\"submit\" name=\"submit_select\" value=\"".T_EDIT."\">\n");
	if($curFile == 'data.scen')echo("<input type =\"submit\" name=\"submit_select\" value=\"".T_EDIT."\">\n");
	echo("</form></td>");
	echo("</table>");

	echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #A9BCF5; color : #000000; padding : 4px; width : 98%; height:514px; overflow : auto; \">\n");
	$len = readAnyFile(1,$curFile);
	showAnyFile($len);
	echo("</div>\n");
      }
    else if ($curEditFlag == 1)
      {
	// open file
	$tempFile = $servuino.$curFile;
	$fh = fopen($tempFile, "r") or die("Could not open file $tempFile!");
	// read file contents
	$data = fread($fh, filesize($tempFile)) or die("Could not read file $tempFile!");
	// close file
	fclose($fh);
	echo("<form name=\"f_edit_file\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"edit_file\">\n");
	echo("<input type=\"hidden\" name=\"file_name\" value=\"$tempFile\">\n");
	echo("<table><tr><td>");
	if($curFile == 'sketch.pde')echo("<input type =\"submit\" name=\"submit_edit\" value=\"".T_LOAD."\">\n");
	if($curFile == 'data.scen')echo("<input type =\"submit\" name=\"submit_edit\" value=\"".T_RUN."\">\n");
	echo("</td></tr><tr><td><textarea name=\"file_data\" cols=64 rows=34>$data</textarea></td></tr></table>");  
	echo("</form><br>");

      }
    else
      echo("Nothing to show1");

    if($ready)echo("$ready");      
    echo("</div>\n");
   }
 else
   echo("Nothing to show2");

//================================================================
echo("</div>\n"); // Right end
//================================================================

echo("<div id=error style=\" background:yellow; float:left; width:100%;\">\n");
echo("[Webuino Version 2011-12-17] Any errors will be shown here<br>");
$file = $servuino.'g++.error';
showAnyFile($file);
$file = $servuino.'exec.error';
showAnyFile($file);
$file = $servuino.'data.error';
showAnyFile($file);
echo("</div>\n"); // Error window

echo("</div>\n"); // Main
?>

