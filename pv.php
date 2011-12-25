<?
include("php-viking/viking.php");


function pv()
{
  global $par;

  $func = $par['pv'];
  $inc  = $par['inc'];
  $inc++;
  $par['inc'] = $inc;

  if($inc==1)viking_7_script(1);
  if($inc==3)viking_7_mainmenu(1);

  if($inc==4)viking_4_login_logout();

  if($inc==2)viking_4_login_Form();
  if($inc==11)viking_4_adduser_Form();

  if($inc==10)viking_4_showUserLoggedIn();
  if($inc==14)viking_4_addUser_Link();
  if($inc==11)viking_7_current(1);

  //==================================  
  if($func == 'start')
    {
      //if($inc==9)viking_7_library(1);
    }
  if($func == 'load')
    {
      if($inc==6)viking_7_load(1);
    }
  if($func == 'board')
    {
      if($inc==5)viking_7_menu(1);
      //if($inc==9)viking_7_winLog(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==6)viking_7_canvas(1); 
      //if($inc==7)viking_7_isMap(1);
      if($inc==6)viking_7_pinValues(1);
    }
  if($func == 'sketch')
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_data(1);
      if($inc==7)viking_7_anyFile(1);
      if($inc==9)viking_7_winSerLog(1);
    }
  if($func == 'log')
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_winSerial(1);
      if($inc==9)viking_7_winLog(1);
    }
  if($func == 'help')
    {
      if($inc==3)viking_4_addUser_Link();
    }
  if($func == 'register')
    {
      if($inc==6)viking_7_applyAccount(1);
    }
  if($func == 'pv')
    {
      echo("pv-$inc");

    }
  //================================== 
  if($inc==15){viking_7_error(1); viking_lib_showError();viking_lib_showWarning();}

}
?>