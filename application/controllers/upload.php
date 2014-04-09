<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Upload extends MY_Controller {

  public function index()
  {
    $this->data['content'] = $this->twig->render('upload_form' , $this->data );
    $this->twig->display('main_tpl' , $this->data );
  }

  public function proced()
  {
    $config['upload_path'] = './replay/';
    $config['allowed_types'] = 'w3g';
    $config['max_size']	= '2000';

    $this->load->library('upload', $config);
    $this->load->library('reshine/reshine');

    $this->load->helper('file');

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
      //krumo($postData);
      $uploaded_file = $this->upload->data();
      //krumo( $uploaded_file );

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

      if ( $this->reshine->extra['parsed'] == true ) {

        $this->data['message'] = 'Replay uploaded successfully. <a href="view_replay.php?file" > View details </a>';


        $this->data['reply'] = $this->reshine;

        $this->data['content'] = $this->twig->render('success_uploaded' , $this->data );


        $this->twig->display('main_tpl' , $this->data );
      }



    }

  }

}
