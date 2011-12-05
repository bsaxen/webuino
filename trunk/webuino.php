<?

define('T_UPLOAD_SKETCH','Upload Sketch');
// Open database
//$pswd  = MYSQL_PSWD;
//$dbCon = openDatabase('proj3','root',$pswd);

//====================================================================
// Upload Sketch
//====================================================================
echo("<form name=\"upload_sketch\" action=\"$ix\" method=\"post\" enctype=\"multipart/form-data\"> ");
echo("<input type=\"hidden\" name=\"action\" value=\"upload_sketch\">");
echo("<input type=\"file\" name=\"sketch_file\" value=\"\">");
echo("<input type =\"submit\" name=\"form_submit\" value=\"".T_UPLOAD_SKETCH."\">");
echo("</form>");

selectSketch();




?>