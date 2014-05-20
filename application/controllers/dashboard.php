<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 5/11/14
 * Time: 10:57 PM
 */

class Dashboard extends MY_AdminController {

  public function index()
  {

    $this->load->model('User_model');

    $this->data['pagetitle'] = "Members";
    $preData['users'] = $this->User_model->getUserInfo();

    if( $this->user->has_permission('admin')) {
      $preData['delete_user'] = true;
    }

    $this->data['content'] = $this->twig->render('panel/users' , $preData );

    $this->displayRender();
  }//index


  protected function displayRender()
  {

    if( $this->validate ) {
      $menu['type'] = 'submenu';
      $menu['item'] = array(
          'My profile' => '/dashboard',
          'Create user' => '/dashboard',
          'Edit permision' => '/dashboard'
      );
      $this->data['submenu'] = $this->twig->render('panel/admin_menu' , $menu);
    }

    $this->twig->display('main_tpl' , $this->data );
  }

}//Dashboard