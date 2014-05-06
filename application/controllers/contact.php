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

    $this->data['content'] = $this->twig->render('contact_form' , $this->data );

    if(isset($_POST['send'])) {

      $uploadFlag = true;
      $formItem = true;

      $this->load->library('email');
      $this->load->helper('file');

      //$config['protocol'] = 'sendmail';
      $config['email']['mailtype'] = "html";

      $config['upload']['upload_path'] = APPPATH . $this->config->item('mailstorage');
      $config['upload']['allowed_types'] = 'w3g';
      $config['upload']['max_size'] = '3000';

      $this->load->library('upload' , $config['upload'] );

      $this->email->initialize($config['email']);

    // put post in variable
      $POST = $_POST;

      if( empty($POST['name']) && empty($POST['subject']) && empty($POST['email']) && empty($POST['messages']) )
      {
        $uploadFlag = false;
        $formItem = false;
      }

      if( $_FILES['file_upload']['error'] == 4 )
      {
        $isFile = false;
      }
      else
      {
        $isFile = true;
      }


      if ( $isFile )
      {
        if( ! $this->upload->do_upload('file_upload') )
        {
          $this->data['message'] = "<div class='message error'>" . $this->upload->display_errors() . "</div>";
          $uploadFlag = false;
        }
        else
        {
          $uploadFlag = true;
          $uploaded_file = $this->upload->data();
          $this->email->attach( $uploaded_file['full_path'] );
        }
      }


      $message = "
        <h2>Dota email form:</h2>
        <p><b>First/Last name:</b> {$POST['name']} </p>
        <p><b>Subject:</b> {$POST['subject']} </p>
        <p><b>Email:</b> {$POST['email']}</p>
        <p><b>Message:</b> {$POST['messages']}</p>
      ";

      $this->email->from('dota@elitearea.org', 'Dota parser');
      $this->email->to('dexterns88@gmail.com');
      //$this->email->cc('another@another-example.com');

      $this->email->subject('Dota webform email');
      $this->email->message( $message );

      if( $uploadFlag )
      {
        if( $this->email->send() )
        {
          $this->data['message'] = "<div class='message status'>Email successfully sent!</div>";
        }
        else
        {
          $this->data['message'] = "<div class='message error'>Something wrong with sending email please try later!</div>";
        }

        if ( $isFile )
        {
          unlink( $uploaded_file['full_path'] );
        }
      }
      else
      {
        if( $uploadFlag == false && $formItem == false )
        {
          $this->data['message'] = "<div class='message error'>You can't send empty form!</div>";
        }
      }

    }

    $this->twig->display('main_tpl' , $this->data );
  }

}