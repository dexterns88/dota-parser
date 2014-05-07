<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Upload extends MY_Controller {

  var $model_date;

  public function index()
  {
    $this->data['pagetitle'] = "Upload replay";
    $this->data['keywords'] = ',upload dota replay';

    $this->data['content'] = $this->twig->render('upload_form' , $this->data );
    $this->twig->display('main_tpl' , $this->data );
  }

  public function proced()
  {

    $this->load->model('save_replay');

    $this->load->helper( array( 'file' , 'date' ) );
    $time = time();

    //$config['upload_path'] = './replay/';
    $config['upload_path'] = $this->storage;
    $config['allowed_types'] = 'w3g';
    $config['max_size']	= '2000';
    $config['file_name'] = "reply_" . $time;

    $this->load->library('upload', $config);
    $this->load->library(array('reshine/reshine','theme_view'));


    $postData = $_POST;

    if ( ! $this->upload->do_upload('replay_file') )
    {
      $this->data['message'] = "<div class='message error'>" . $this->upload->display_errors() . "</div>";
      $this->index();
    }
    else if( empty( $postData['replay_title'] ) )
    {
      $this->data['message'] = "<div class='message error'>Title is required</div>";
      $this->index();
    }
    else
    {
      $uploaded_file = $this->upload->data();
      $this->reshine->replay( $uploaded_file['full_path'] );

      $model_date['saver'] = $this->reshine->game['saver_name'];
      $model_date['title'] = $_POST['replay_title'];
      $model_date['time'] = $time;
      $model_date['link'] = $uploaded_file['orig_name'];

      $this->reshine->extra['title'] = $model_date['title'];

      if( "Automatic" != $postData['replay_winner'] ) {
        $this->reshine->extra['winner'] = ( $postData['replay_winner'] == "Sentinel" ? "Sentinel" : "Scourge" );
      }
      else if(isset( $this->reshine->extra['parsed_winner'])) {
        $this->reshine->extra['winner'] = $this->reshine->extra['parsed_winner'];
      }
      else {
        $this->reshine->extra['winner'] = "Unknown";
      }

      $this->reshine->extra['text'] = $postData['replay_text'];
      $this->reshine->extra['original_filename'] = $uploaded_file['file_name'];

      $txtFile = $uploaded_file['file_path'] . $uploaded_file['raw_name'] . ".txt";
      $origin = $uploaded_file['file_path'] . $uploaded_file['raw_name'] . ".w3g";

      $output = $this->theme_view->render( $this->reshine , $uploaded_file['raw_name'] );

      $test = write_file( $txtFile , serialize( $output ) );

      $insertRes = $this->save_replay->insert($model_date);

      if($insertRes == false )
      {
        $file_save = $config['upload_path'] . $uploaded_file['file_name'];
        $file_txt = $config['upload_path'] . $uploaded_file['raw_name'] . ".txt";
        unlink( $file_save );
        unlink( $file_txt );

        $this->data['message'] = "<div class='message error'>error with uploading replay please contact us to report issues</div>";
        $this->index();
      }
      else
      {
        $links = "/view/save/" . $uploaded_file['raw_name'];
        if ( $this->reshine->extra['parsed'] == true ) {
          $this->data['message'] = "Replay uploaded successfully. <a href='{$links}' > View details </a>";
          $this->data['reply'] = $this->reshine;
          $this->data['content'] = $this->twig->render('success_uploaded' , $this->data );
          $this->twig->display('main_tpl' , $this->data );
        }
      }// if query true

    }

  }

}
