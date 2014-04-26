<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class page404 extends MY_Controller {

  public function index()
  {
    $this->data['body_class'] = "page404";
    $this->data['content'] = "<h1>Page not found</h1>";
    $this->data['content'] .= "<h2>Content you're looking for is not here!!!</h2>";
    $this->data['content'] .= "<div class='hero-search'><img width='460px' src='/images/site/404_2.png' alt='404'></div>";

    $this->twig->display('main_tpl' , $this->data );
  }

}