<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Upload extends MY_Controller {

  var $model_date;

  public function index()
  {
    $this->data['pagetitle'] = "Upload replay";
    $this->data['content'] = $this->twig->render('upload_form' , $this->data );
    $this->twig->display('main_tpl' , $this->data );
  }

  public function proced()
  {

    $this->load->model('save_replay');

    $this->load->helper( array( 'file' , 'date' ) );
    $time = time();

    $config['upload_path'] = './replay/';
    $config['allowed_types'] = 'w3g';
    $config['max_size']	= '2000';
    $config['file_name'] = "reply_" . $time;

    $this->load->library('upload', $config);
    $this->load->library('reshine/reshine');



    //$datestring = "Year: %Y Month: %m Day: %d - %h:%i %a";
    //$localTime =  gmt_to_local( $time , 'UP1' , true  );
    //krumo( mdate($datestring, $time) );
    //krumo(mdate($datestring , $localTime ));


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
      $test = write_file( $txtFile , serialize( $this->reshine ) );

      $model_date['saver'] = $this->reshine->game['saver_name'];
      $model_date['title'] = $_POST['replay_title'];
      $model_date['time'] = $time;
      $model_date['link'] = $uploaded_file['orig_name'];

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
        if ( $this->reshine->extra['parsed'] == true ) {
          $this->data['message'] = 'Replay uploaded successfully. <a href="view_replay/" > View details </a>';
          $this->data['reply'] = $this->reshine;
          $this->data['content'] = $this->twig->render('success_uploaded' , $this->data );
          $this->twig->display('main_tpl' , $this->data );
        }
      }// if query true

    }

  }

}
