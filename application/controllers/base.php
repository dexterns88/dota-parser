<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Base extends CI_Controller {

  protected $data = array();

  function __construct()
  {
    parent::__construct();

    $this->load->spark('krumo/0.0.1');
    $this->load->spark('Twig/0.0.1');

  }

}