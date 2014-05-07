<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class News extends MY_Controller {

  static $page;
  static $itemsPager = 5;

  public function index()
  {
    redirect('/news/page');
  }

  public function page( $i = 0 ){
    $this->data['title'] = 'News / w3xSilverCloud';

    $this->data['keywords'] = ',dota news';

    $this->load->library('pagination');
    $this->load->model('news_modal');

    $this->data['pagetitle'] = "News";

    $query_pagination['limit']['start'] = $i;
    $query_pagination['limit']['count'] = self::$itemsPager;

    $news['content'] = $this->news_modal->read( $query_pagination );

    $qp['numrow'] = true;

    $config = $this->pagerConf( $this->news_modal->read( $qp ) );

    $this->pagination->initialize($config);
    $news['pagination'] = $this->pagination->create_links();

    $this->data['content'] = $this->twig->render('news_list', $news);


    $this->twig->display('main_tpl' , $this->data);
  }

  private function pagerConf( $page )
  {
    $config['base_url'] = '/news/page';
    $config['total_rows'] = $page; //100;
    $config['per_page'] = self::$itemsPager;
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