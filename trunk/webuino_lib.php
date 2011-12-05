<?
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
      necho("Error openDatabase($db,$secret)<br>");
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


?>
