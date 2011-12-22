<?
include("php-viking/viking.php");

$inc = 0;

if($_GET['pv'])
  $func = $_GET['pv'];
 else
   $func = $_SESSION['pv'];

$_SESSION['pv'] = $func;

//echo("pv = $func <br>");

function pv()
{
  global $func,$inc;

  $inc++;

  if($inc==1)viking_7_script();

  if($inc==2)viking_4_login_logout();
  if($inc==11)viking_4_login_Form();
  if($inc==12)viking_4_adduser_Form();

  if($inc==11)viking_4_showUserLoggedIn();
  if($inc==14)viking_4_addUser_Link();
  if($inc==10)viking_7_error();

  
  if($func == 'start')
    {

    }
  if($func == 'lib')
    {
      if($inc==8)viking_7_library();
    }
  if($func == 'sim')
    {
      if($inc==6)viking_7_winLog();
      if($inc==13)viking_7_winSerial();
      if($inc==8)viking_7_menu();
      if($inc==9)viking_7_canvas();
      if($inc==5)viking_7_current();
    }
  if($func == 'config')
    {
      if($inc==4)viking_4_addUser_Link();
    }
  if($func == 'advanced')
    {
      if($inc==8)viking_7_data();
      //if($inc==9)viking_7_editFile();
      if($inc==9)viking_7_anyFile();
    }
  if($func == 'help')
    {
      if($inc==3)viking_4_addUser_Link();
    }
  if($func == 'login')
    {
      if($inc==3)viking_4_addUser_Link();
    }
  if($func == 'pv')
    {
      echo("pv-$inc");

    }
}
?>