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
readSerial('data.serial');
readStatus();


$logLen = readAnyFile($curLog);

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
    $logLen = readAnyFile($curLog);
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
echo("<body onload=\"draw();\" link=\"black\" alink=\"black\" vlink=\"black\">\n");
echo("<div id=\"main\" style=\" background:white; float:left; width:100%;\">");
echo("  <div id=\"left\" style=\" background:white; float:left; width:100%; height: 40px;\">");
echo("    <div style=\"text-align:right;font-size:20px; background:white; float:left; width:100%;\">");
echo("         <a href=index.php?ac=step&x=1>Reset</a>\n");
$temp = $curStep - 1;
echo("         <a href=index.php?ac=step&x=$temp>Backward</a>\n");
$temp = $curStep + 1;
echo("         <a href=index.php?ac=step&x=$temp>Forward</a>\n");
echo("      <a href=index.php?ac=menu&x=logA> LogA </a>\n");
echo("      <a href=index.php?ac=menu&x=logB> LogB </a>\n");
echo("      <a href=index.php?ac=menu&x=config> Library </a>\n");
echo("      <a href=index.php?ac=menu&x=file> Data </a>\n");
echo("    </div>");
echo("    <div style=\"text-align:center;font-size:15px; background:white; float:left; width:100%;\">");
echo("      Loaded Sketch: $curSketch Now: $curStep   Simulation Length: $curSimLen steps");
echo("    </div>");

echo("  </div>");
echo("  <div id=\"left\" style=\" background:white; float:left; width:50%\">");
echo("    <div id=\"above\" style=\" background:white; float:left; width:100%;\">");
echo("      <canvas id=\"tutorial\" width=\"$wBoard\" height=\"$hBoard\"></canvas>\n");
echo("    </div>");

echo("    <div id=\"below\" style=\" background:white; float:left; width:100%;\">");
    // Serial window
    echo("<div id=\"serWin\"t style=\"font-family: Courier,monospace;float:left; border : solid 3px #C0C0C0; background : #E3F6CE; color : #000000; padding : 4px; width : 97%; height:250px; overflow : auto; \">\n");
    showSerial($curStep);
    echo("</div>\n"); 

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
	
	//if($curFile == 'sketch.pde')echo("<td><a href=index.php?ac=edit_file>".T_EDIT."</a></td></tr>");
	echo("</table>");
	echo("</div><div id=\"simList\" style=\"float:left; border : solid 1px #000000; background : #A9BCF5; color : #000000; padding : 4px; width : 98%; height:514px; overflow : auto; \">\n");
	$len = readAnyFile($curFile);
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
	echo("<br><br><form name=\"f_edit_file\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\">\n ");
	echo("<input type=\"hidden\" name=\"action\" value=\"edit_file\">\n");
	echo("<input type=\"hidden\" name=\"file_name\" value=\"$tempFile\">\n");
	if($curFile == 'sketch.pde')echo("<input type =\"submit\" name=\"submit_edit\" value=\"".T_LOAD."\">\n");
	if($curFile == 'data.scen')echo("<input type =\"submit\" name=\"submit_edit\" value=\"".T_RUN."\">\n");
	echo("<textarea name=\"file_data\" cols=64 rows=33>$data</textarea>");  
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
$file = $servuino.'g++.error';
showAnyFile($file);
$file = $servuino.'exec.error';
showAnyFile($file);
$file = $servuino.'data.error';
showAnyFile($file);
echo("</div>\n"); // Error window

echo("</div>\n"); // Main
?>

