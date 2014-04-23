<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  protected $data = array();

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
      2 => array( "url" => "/view" , "title" => "Replay database")
    );

    $this->data['nav'] = $this->twig->render('menu' , $menu );

  }

}