<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class View extends MY_Controller {

  var $query;

  public function index()
  {
    $this->load->model('save_replay');

    $query['lists'] = $this->save_replay->view_all();

    $this->data['content'] = $this->twig->render('list_view' , $query );


    $this->twig->display('main_tpl' , $this->data );
  }

  public function save($i)
  {
    krumo($i);
  }

}