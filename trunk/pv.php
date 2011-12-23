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

  if($inc==1)viking_7_script(1);
  if($inc==2)viking_7_mainmenu(1);
  if($inc==3)viking_4_login_logout();
  if($inc==11)viking_4_login_Form();
  if($inc==12)viking_4_adduser_Form();

  if($inc==15)viking_4_showUserLoggedIn();
  if($inc==14)viking_4_addUser_Link();

  //==================================  
  if($func == 'start')
    {

    }
  if($func == 'lib')
    {
      if($inc==8)viking_7_library(1);
    }
  if($func == 'sim')
    {
      if($inc==6)viking_7_winLog(1);
      if($inc==13)viking_7_winSerial(1);
      if($inc==8)viking_7_menu(1);
      if($inc==9)viking_7_canvas(1);
      if($inc==9)viking_7_current(1);
      if($inc==10)viking_7_pinValues(1);
    }
  if($func == 'config')
    {
      if($inc==4)viking_4_addUser_Link();
    }
  if($func == 'advanced')
    {
      if($inc==8)viking_7_data(1);
      //if($inc==9)viking_7_editFile(1);
      if($inc==9)viking_7_anyFile(1);
    }
  if($func == 'help')
    {
      if($inc==3)viking_4_addUser_Link();
    }
  if($func == 'register')
    {
      if($inc==9)viking_7_applyAccount(1);
    }
  if($func == 'login')
    {
      if($inc==11)viking_4_addUser_Link();
    }
  if($func == 'pv')
    {
      echo("pv-$inc");

    }
  //================================== 
  if($inc==15){viking_7_error(1); viking_lib_showError();viking_lib_showWarning();}

}
?>