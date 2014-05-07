<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class View extends MY_Controller {

  var $query , $file , $replayConf , $replay;

  private $itemsPager = 20;

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

    $this->data['title'] = 'Save listing / w3xSilverCloud';
    $this->data['keywords'] = ',dota preview replay,dota player analitics,game analitics';

    $query_pagination['page'] = $i;
    $query_pagination['item_per_page'] = $this->itemsPager;

    $query['lists'] = $this->save_replay->view_all( 'limit', $query_pagination );

    $config = $this->pagerConf( $this->save_replay->view_all( 'numrow', $query_pagination ) );

    $this->pagination->initialize($config);
    $query['pagination'] = $this->pagination->create_links();

    $this->data['content'] = $this->twig->render('list_view' , $query );
    $this->twig->display('main_tpl' , $this->data );


  }

  public function ajax($i = 0)
  {
    $flag = false;
    if( ! isset($_POST['type']) ) {
      $flag = true;
    } else {
      if( $_POST['type'] != 'ajax' ) {
        $flag = true;
      }
    }

    if( $flag ) {
      show_404();
    }

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
    $this->data['title'] = 'Replay details / w3xSilverCloud';

    array_push( $this->data['jsplugin'] , "/script/jquery.mCustomScrollbar.min.js" );
    array_push( $this->data['jsplugin'] , "/script/jquery.mousewheel.min.js" );

    $file = $i;
    $replayConf['storage'] = $this->storage;
    $replayConf['storageRaw'] = 'replay\\';
    $replayConf['file_full'] = $replayConf['storage'] . $file;


    $this->load->library(array('reshine/reshine' , 'theme_view') );
    $this->load->helper('file');

    if( file_exists( $replayConf['file_full'] . '.txt'  ) )
    {
      $txt_file = read_file( $replayConf['file_full'] . '.txt' );
      $replay = unserialize($txt_file);
    }
    else
    {
      $this->data['message'] = "<div class='message error'>File doesn't exist</div>";
    }

    if( isset($replay) ) {
      $json_theme['replay'] = $replay;
      $this->data['content'] = $this->twig->render('save_prev' , $json_theme );
    }
    $this->twig->display('main_tpl' , $this->data );


  }

  private function pagerConf( $page )
  {
    $config['base_url'] = '/view/page';
    $config['total_rows'] = $page; //100;
    $config['per_page'] = $this->itemsPager;
    $config['full_tag_open'] ="<ul class='pager'>";
    $config['full_tag_close'] ="</ul>";
    $config['num_tag_open'] = '<li>';
    $config['num_tag_close'] = '</li>';
    $config['prev_tag_open'] = '<li class="prev">';
    $config['prev_tag_close'] = '</li>';
    $config['next_tag_open'] = '<li class="next">';
    $config['next_tag_close'] = '</li>';
    $config['last_tag_open'] = '<li class="last">';
    $config['last_tag_close'] = '</li>';
    $config['first_tag_open'] = '<li class="first">';
    $config['first_tag_close'] = '</li>';
    $config['cur_tag_open'] = '<li class="current">';
    $config['cur_tag_close'] = '</li>';
    $config['prev_link'] = '<i class="fa fa-angle-left"></i>';
    $config['first_link'] = '<i class="fa fa-angle-double-left"></i>';
    $config['next_link'] = '<i class="fa fa-angle-right"></i>';
    $config['last_link'] = '<i class="fa fa-angle-double-right"></i>';
    return $config;
  }

}