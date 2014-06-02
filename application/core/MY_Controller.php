<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  protected $data = array();
  protected $storage;
  protected $admin;

  function __construct()
  {
    parent::__construct();

    //$this->load->spark('google-analytics-lib/x.x.x');

    if( ! isset($this->admin) )
    {
      $this->load->spark('google-analytics-lib/2.0.0');
    }

    $this->data['jsplugin'] = array();

  //load ci
    $this->load->helper('url');

    $this->load->spark('krumo/0.0.1');
    $this->load->spark('twig/0.0.1');

    if( defined('ganalytics') ) {
      $this->data['analitics'] = ganalytics;
    }



    $this->data['url'] = base_url();

    $this->data['styles'] = array (
      '/stylesheets/albatross_api.css'
    );

    $thisUrl = uri_string();
    $thisUrl =  preg_split("/\//" , $thisUrl);
    $menu['curentUrl'] = "/" . $thisUrl[0];
    $menu['items'] = array(
      0 => array( "url" => "/" , "title" => "Home"),
      1 => array( "url" => "/upload", "title" => "Upload replay" ),
      2 => array( "url" => "/view" , "title" => "Replay database"),
      3 => array( "url" => "/news" , "title" => "News"),
      4 => array( "url" => "/contact" , "title" => "Contact"),
      5 => array( "url" => "https://github.com/dexterns88/w3xsilvercloude/issues" , "title" => "Report issue" , "target" => "_blank")
    );

    //render menu
    $this->data['nav'] = $this->twig->render('menu' , $menu );

    $this->storage = APPPATH . $this->config->item('storage');

    if (!is_dir( $this->storage )) {
      mkdir( $this->storage , 0766, TRUE);
    }

  }

} // MY_Controller

class MY_AdminController extends MY_Controller {

  private $username;
  private $password;
  protected $validate = false;

  function __construct()
  {
    $this->admin = true;

    parent::__construct(); // init parent
    $this->load->library( array('users/user','users/user_manager') );
    $this->load->helper('security');

    $tmpData['url'] = "/" . uri_string();

    if( ! $this->user->validate_session() )
    {
      if( $tmpData['url'] != '/panel/login' )
      {
        redirect('/panel/login');
      }
    } //validate_session
    else
    {

      $this->validate = true;
      //add ckeditor js
      array_push( $this->data['jsplugin'] , "/script/ck/ckeditor.js" , "/script/ck/adapters/jquery.js" ,  "/script/ckbuild.js" );

      $this->data['user'] = array(
        'user' => $this->user->get_login(),
        'uid' => $this->user->get_id(),
        'name' => $this->user->get_name(),
        'email' => $this->user->get_email(),
        'image' => $this->user->get_custom_field('image')
      );

      $menu['type'] = "main-admin";
      $menu['item'] = array(
        'Panel' => '/panel/addnews',
        'Dashboard' => '/dashboard'
      );
      $this->data['adminmenu'] = $this->twig->render('panel/admin_menu' , $menu );

    }

  } //constructor

  public function titleSet($title)
  {
    if( !isset($this->data['pagetitle']) )
      $this->data['pagetitle'] = $title;
  }

} //class