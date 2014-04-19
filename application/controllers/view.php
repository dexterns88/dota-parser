<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class View extends MY_Controller {

  var $query , $file , $replayConf , $replay;

  public function index()
  {
    $this->data['pagetitle'] = "Replay list";

    $this->load->model('save_replay');

    $this->data['title'] = 'Save listing';

    $query['lists'] = $this->save_replay->view_all();

    $this->data['content'] = $this->twig->render('list_view' , $query );


    $this->twig->display('main_tpl' , $this->data );
  }

  public function save($i)
  {
    $this->data['title'] = "Replay details";

    array_push( $this->data['jsplugin'] , "/script/jquery.mCustomScrollbar.min.js" );
    array_push( $this->data['jsplugin'] , "/script/jquery.mousewheel.min.js" );

    $file = $i;
    $replayConf['storage'] = './replay/';
    $replayConf['storageRaw'] = 'replay\\';
    $replayConf['file_full'] = $replayConf['storage'] . $file;


    $this->load->library(array('reshine/reshine' , 'theme_view') );
    $this->load->helper('file');

    if( file_exists( $replayConf['file_full'] . '.txt'  ) )
    {
      $txt_file = read_file( $replayConf['file_full'] . '.txt' );
      $replay = unserialize($txt_file);
    }
    else if ( file_exists( $replayConf['file_full'] . ".w3g" ) )
    {
      //$fileSave = FCPATH . $replayConf['storageRaw'] . $file . ".w3g"; // full path server
      $fileSave = $replayConf['file_full'] . '.w3g';
      $replay = $this->reshine->replay( $fileSave );
      $txt_file = write_file( $replayConf['file_full'] . '.txt' , serialize( $this->reshine ) );
    }
    else
    {
      $data['message'] = "<div class='message error'>File doesn't exist</div>";
    }

    //$version = sprintf('%02d', $replay->header['major_v']);

    //krumo( $replay );

    if( isset($replay) ) {
      $json_theme['replay'] = $this->theme_view->render( $replay , $i );
      $this->data['content'] = $this->twig->render('save_prev' , $json_theme );
    }
    $this->twig->display('main_tpl' , $this->data );


  }

}