<?
include("php-viking/viking.php");


function pv()
{
  global $par;

  // Set start page
  if(!$par['pv'])$par['pv'] = 'start';

  $user = $par['user'];
  $func = $par['pv'];
  $inc  = $par['inc'];

  $inc++;
  $par['inc'] = $inc;

  if($inc==1){viking_7_script(1);viking_lib_tinyMCE();}
  if($inc==3)viking_7_mainmenu(1);
  if($inc==4)viking_4_login_logout();
  if($inc==2)viking_4_login_Form();

  if($inc==11)viking_4_showUserLoggedIn();
  if($inc==12)viking_7_current(1);
  if($inc==13)viking_7_loginCounter(1);

  //==================================  
  if($func == 'start')
    {
      if($inc==6)viking_7_start();
    }
  if($func == 'tutorial')
    {
      if($inc==6)viking_7_tutorial();
    }
  if($func == 'about')
    {
      if($inc==6)viking_7_about();
    }
  if($func == 'library' && $user)
    {
      if($inc==10)viking_7_downloadSketch(1);
      if($inc==6)viking_7_library(1);
      if($inc==9)viking_7_editSketch(1);
    }
  if($func == 'large_sketch' && $user)
    {
      if($inc==10)viking_7_editSketch(1);
    }
  if($func == 'large_file' && $user)
    {
      if($inc==10)viking_7_editFile(1);
    }
  if($func == 'large_graph' && $user)
    {
      if($inc==10)viking_7_graph(1);
    }
  if($func == 'faq')
    {
      if($inc==10)viking_7_faq(1);
    }
  if($func == 'load')
    {
      if($inc==7){viking_7_error(1); viking_lib_showError();viking_lib_showWarning();}
      if($inc==6)viking_7_only_load(1);
      if($inc==9)viking_7_editSketch(1);
    }
  if($func == 'board' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==6)viking_7_canvas(1); 
      //if($inc==7)viking_7_isMap(1);
      if($inc==6)viking_7_pinValues(1);
    }
  if($func == 'advanced' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==6)viking_7_canvas(1); 
      if($inc==10)viking_7_graph(1);
    }
  if($func == 'sketch' && $user)
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_data(1);
      if($inc==7)viking_7_editFile(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==9)viking_7_pinValues(1);
    }
  if($func == 'log' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_winSerial(1);
      if($inc==9)viking_7_winLog(1);
    }
  if($func == 'help')
    {
      if($inc==6)viking_7_help();
    }
  if($func == 'register')
    {
      if($inc==6)
	{
	  viking_7_register(1);
	  viking_7_applyAccount(1);
	}
    }
  if($func == 'admin' && $user == 'admin')
    {
      if($inc==6)viking_4_showUserByName();

      if($inc==9) viking_4_addUser_Link();
      if($inc==9) viking_4_delUser_Link();
      if($inc==9) viking_4_delUser_Form();
      if($inc==9) viking_4_adduser_Form();
      if($inc==9) viking_7_accessControl(1);
    }
  if($func == 'pv')
    {
      echo("pv-$inc");

    }
  //================================== 
  //if($inc==15){viking_7_error(1); viking_lib_showError();viking_lib_showWarning();}

}
?>