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

  if($inc==1)
    {
      if($func=='board')
	  viking_7_draw(1);
	else
	  viking_7_draw_void(1);

      viking_lib_tinyMCE();
    }
  if($inc==3)viking_7_mainmenu(1);
  //if($inc==4)viking_4_login_logout();
  if($inc==2)viking_4_login_Form();

  if($inc==11)viking_4_showUserLoggedIn();
  if($inc==12)viking_7_current(1);
  if($inc==13)viking_7_loginCounter(1);

  //==================================  

  if($func == 'start')
    {
      if($inc==6)viking_7_start(1);
    }
  if($func == 'tutorial')
    {
      if($inc==6)viking_7_tutorial(1);
    }
  if($func == 'about')
    {
      if($inc==6)viking_7_about(1);
    }
  if($func == 'edit_sketch' && $user)
    {
      if($inc==6)viking_7_edit_sketch(1);
      if($inc==9)viking_7_editSketch(1);
    }
  if($func == 'edit_scenario' && $user)
    {
      if($inc==6)viking_7_edit_scenario(1);
      if($inc==9)viking_7_editScenario(1);
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
  if($func == 'news')
    {
      if($inc==10)viking_7_news(1);
    }
  if($func == 'load' && $user)
    {
      if($inc==7)viking_7_error(1);
      if($inc==6)viking_7_only_load(1);
      if($inc==9)viking_7_editSketch(1);
    }
  if($func == 'board' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==6)viking_7_canvas(1); 
      //if($inc==7)viking_7_isMap(1);
      //if($inc==6)viking_7_pinValues(1);
    }
  if($func == 'source' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==6)viking_7_source(1); 
    }
  if($func == 'graph_status' && $user )
    {
      //if($inc==10)viking_7_menu(1);
      if($inc==10)viking_7_pinStatus(1);
      if($inc==10)viking_7_graph_status(1);
    }
  if($func == 'graph_scenario' && $user )
    {
      //if($inc==5)viking_7_menu(1);
      if($inc==10)viking_7_pinScenario(1);
      if($inc==10)viking_7_graph_scenario(1);
    }
  if($func == 'sketch' && $user)
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_data(1);
      if($inc==7)viking_7_editFile(1);
      if($inc==9)viking_7_winSerLog(1);
      if($inc==9)viking_7_pinValues(1);
    }
  if($func == 'debug' && $user)
    {
      if($inc==6)viking_7_data(1);
      if($inc==7)viking_7_editFile(1);
    }
  if($func == 'log' && $user )
    {
      if($inc==5)viking_7_menu(1);
      if($inc==6)viking_7_winSerial(1);
      if($inc==9)viking_7_winLog(1);
    }
  if($func == 'help')
    {
      if($inc==6)viking_7_help(1);
    }
  if($func == 'create')
    {
      if($inc==6)viking_7_create(1);
    }
  if($func == 'copy')
    {
      if($inc==6)viking_7_copy(1);
    }
  if($func == 'delete')
    {
      if($inc==6)viking_7_delete(1);
    }
  if($func == 'upload')
    {
      if($inc==6)viking_7_upload(1);
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
      if($inc==6)viking_4_showUserByName(1);

      if($inc==9) viking_4_addUser_Link(1);
      if($inc==9) viking_4_delUser_Link(1);
      if($inc==9) viking_4_delUser_Form(1);
      if($inc==9) viking_4_adduser_Form(1);
      if($inc==9) viking_7_accessControl(1);
    }
  if($func == 'list_user' && $user == 'admin')
    {
      if($inc==6)viking_4_showUserByName(1);
      if($inc==9)viking_4_super_Form(1);
    }
  if($func == 'add_user' && $user == 'admin')
    {
      if($inc==6) viking_4_addUser_Link(1);
      if($inc==6) viking_4_adduser_Form(1);
    }
  if($func == 'del_user' && $user == 'admin')
    {
      if($inc==6) viking_4_delUser_Link(1);
      if($inc==6) viking_4_delUser_Form(1);
      if($inc==6) viking_7_delAccount(1);
    }
  if($func == 'pv')
    {
      echo("pv-$inc");

    }
  //================================== 
  if($inc==15)
    {
      //  viking_7_error(1); 
      viking_lib_showError();
      viking_lib_showWarning();
    }

}
?>