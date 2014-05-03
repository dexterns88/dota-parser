<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 4/29/14
 * Time: 8:34 PM
 */

class Contact extends MY_Controller {

  public function index() {
    $this->data['title'] = "Contact";
    $this->data['pagetitle'] = "Contact us";
    $this->twig->display('main_tpl' , $this->data );
  }

}