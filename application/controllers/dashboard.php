<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 5/11/14
 * Time: 10:57 PM
 */

class Dashboard extends MY_AdminController {

  public function __construct()
  {
    parent::__construct();
  }

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

  public function profile()
  {
    $data['user'] = $this->data['user'];
    $this->data['content'] = $this->twig->render('panel/user_profile' , $data );
    $this->displayRender();
  }

  public function editprofile()
  {
    $this->data['title'] = "Edit profile";
    $this->load->library('form_validation');


    $this->form_validation->set_rules('username', 'user name', 'required');
    $this->form_validation->set_rules('name', 'first and last name', 'required');
    $this->form_validation->set_rules('email', 'email', 'required');

    if( isset($_POST['update_profile']))
    {
      if($this->form_validation->run() == false )
      {
        $this->data['message'] = writeMessage( validation_errors() , 'error');
      }
      else
      {
        $this->load->model('user_model');
        if( $this->user_model->updateProfile( $_POST['name'] , $_POST['email'] , $this->user->get_id() ) ) {
          $this->data['message'] = writeMessage( 'Profile updated successfully' , 'status');
          $this->data['user']['name'] = $_POST['name'];
          $this->data['user']['email'] = $_POST['email'];
          $this->data['user']['user'] = $_POST['username'];
          $this->user->update_login( $_POST['username'] );
        }
        else
        {
          $this->data['message'] = writeMessage( 'Something wrong please try again' , 'error');
        }
      }
    }

    $this->data['content'] = $this->twig->render('panel/user_edit_form' , $this->data );
    $this->displayRender();
  }

  public function editpasword() {
    $this->data['title'] = "Edit user password";
    $this->data['content'] = $this->twig->render('panel/updatepass' , $this->data );

    if(isset($_POST['update_pass']))
    {
      $this->load->library('form_validation');

      $this->form_validation->set_rules('newpass', 'new password', 'required|matches[renewpass]');
      $this->form_validation->set_rules('renewpass', 're-new password', 'required');

      if($this->form_validation->run() == false )
      {
        $this->data['message'] = writeMessage( validation_errors() , 'error');
      }
      else
      {
        $tmpPass = $_POST['newpass'];
        if( $this->user->update_pw($tmpPass) )
        {
          $this->data['message'] = writeMessage( 'password successfully updated' , 'status');
        }
        else
        {
          $this->data['message'] = writeMessage( 'something wrong with changing pass please try again' , 'error');
        }
      }//end change pass
    }//end if

    $this->displayRender();
  }

  protected function displayRender()
  {

    if( $this->validate ) {
      $menu['type'] = 'submenu';
      $menu['item'] = array(
          'My profile' => '/dashboard/profile',
          'Edit profile' => '/dashboard/editprofile/',
          'Edit pasword' => '/dashboard/editpasword',
//          'Create user' => '/dashboard'
      );
      $this->data['submenu'] = $this->twig->render('panel/admin_menu' , $menu);
    }

    $this->twig->display('main_tpl' , $this->data );
  }

}//Dashboard