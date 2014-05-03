<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 4/29/14
 * Time: 11:25 PM
 */

class Panel extends MY_AdminController {

  public function index()
  {
    $this->twig->display('main_tpl' , $this->data );
  }

  public function addnews()
  {
   $this->titleSet('Create news');
    $this->index();
  }

  public function logout()
  {
    $this->user->destroy_user('/');
  }

}