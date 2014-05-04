<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  protected $data = array();
  protected $storage;

  function __construct()
  {
    parent::__construct();

    $this->data['jsplugin'] = array();

  //load ci
    $this->load->helper('url');

    $this->load->spark('krumo/0.0.1');
    $this->load->spark('twig/0.0.1');

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
      4 => array( "url" => "/contact" , "title" => "Contact")
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

  function __construct()
  {
    parent::__construct(); // init parent
    $this->load->library( array('users/user','users/user_manager') );
    $this->load->helper('security');

    $tmpData['url'] = "/" . uri_string();

    if( ! $this->user->validate_session() )
    {
      $this->data['pagetitle'] = 'Login to panel';
      if( isset($_POST['login'] ) || isset($_POST['enter']) )
      {
        $this->username = encode_php_tags($_POST['username']);
        $this->password = encode_php_tags($_POST['pass']);
        $this->user->login( $this->username , $this->password );
        redirect( $tmpData['url'] );

      }

      if( $this->session->flashdata('error_message') )
      {
        $this->data['message'] = "<div class='message error'>" . $this->session->flashdata('error_message')."</div>";
      }

      $this->data['content'] = $this->twig->render('panel/login_form' , $tmpData );
    } //validate_session
    else
    {
      $this->data['user'] = array(
        'username'=> $this->user->get_login(),
        'id' => $this->user->get_id(),
        'image' => $this->user->get_custom_field('image')
      );
      $menu['item'] = array(
          'Add news' => 'addnews',
          'delete news' => 'newsdelete'
      );
      $this->data['submenu'] = $this->twig->render('panel/admin_menu' , $menu);
    }

  } //constructor

  public function titleSet($title)
  {
    if( !isset($this->data['pagetitle']) )
      $this->data['pagetitle'] = $title;
  }

} //class