<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class View extends MY_Controller {

  var $query , $file , $replayConf , $replay;

  private $itemsPager = 3;

  public function index()
  {
    $this->load->helper('url');
    redirect('/view/page');
  }

  public function page( $i = 0 )
  {
    $this->load->library('pagination');
    $this->load->model('save_replay');
    $this->data['pagetitle'] = "Replay list";

    $query_pagination['page'] = $i;
    $query_pagination['item_per_page'] = $this->itemsPager;

    $query['lists'] = $this->save_replay->view_all( 'limit', $query_pagination );

    $config = $this->pagerConf( $this->save_replay->view_all( 'numrow', $query_pagination ) );

    $this->pagination->initialize($config);
    $query['pagination'] = $this->pagination->create_links();

    $this->data['title'] = 'Save listing';

    $this->data['content'] = $this->twig->render('list_view' , $query );
    $this->twig->display('main_tpl' , $this->data );


  }

  public function ajax($i = 0)
  {
    $this->load->library('pagination');
    $this->load->model('save_replay');

    $query_pagination['page'] = $i;
    $query_pagination['item_per_page'] = $this->itemsPager;

    $query['lists'] = $this->save_replay->view_all( 'limit', $query_pagination );

    $config = $this->pagerConf( $this->save_replay->view_all( 'numrow', $query_pagination ) );

    $this->pagination->initialize($config);
    $query['pagination'] = $this->pagination->create_links();

    $this->data['title'] = 'Save listing';
    $query['ajax'] = true;

    $this->twig->display('list_view' , $query );

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

  private function pagerConf( $page )
  {
    //$config['base_url'] = 'http://example.com/index.php/test/page/';
    $config['base_url'] = '/view/page';
    $config['total_rows'] = $page; //100;
    $config['per_page'] = $this->itemsPager;
    $config['full_tag_open'] ="<ul class='pager'>";
    $config['full_tag_close'] ="</ul>";
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li>';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li>';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li>';
    $config['last_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li>';
    $config['first_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="current">';
    $config['cur_tag_close'] = '</li>';

    return $config;
  }

}