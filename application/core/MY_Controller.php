<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Controller extends CI_Controller {

  protected $data = array();

  function __construct()
  {
    parent::__construct();

  //load ci
    $this->load->helper('url');

    $this->load->spark('krumo/0.0.1');
    $this->load->spark('Twig/0.0.1');

    $this->data['url'] = base_url();


    $this->data['nav'] = $this->twig->render('menu');

  }

}