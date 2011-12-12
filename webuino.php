<?

define('T_UPLOAD_SKETCH','Upload Sketch');





// Open database
//$pswd  = MYSQL_PSWD;
//$dbCon = openDatabase('proj3','root',$pswd);
// GET ==============================================

$temp = $_GET['a4_object_id'];
if($temp)
  {
    $sel_object = $temp;
    $sel_name   = getObjectName($sel_db,$sel_object);
  }


// POST =============================================
if ($_SERVER['REQUEST_METHOD'] == "POST")
  {
    $action = $_POST['action'];
    
    if($action == 'upload_sketch' )
      {
	$steps = $_POST['steps'];
	//echo("Uploading $sketchFile<br>");
	$fil = uploadFile();
	//echo("fil = $fil <br>");
	system("pwd;cp $fil servuino/sketch.pde;");
	system("cd servuino;g++ -o servuino servuino.c;");
	system("cd servuino;./servuino $steps 0;");
      }
  }

//====================================================================
// Upload Sketch
//====================================================================
echo("<form name=\"upload_sketch\" action=\"index.php\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">");
echo("Steps<input type=\"text\" name=\"steps\" value=\"\">");
echo("<input type=\"file\" name=\"import_file\" value=\"\">"); 
echo("<input type =\"submit\" name=\"submit_file\" value=\"".T_UPLOAD_SKETCH."\">");
echo("</form>");

showFile("data.su");

selectSketch();




?>