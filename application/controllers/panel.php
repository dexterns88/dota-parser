<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 4/29/14
 * Time: 11:25 PM
 */

class Panel extends MY_AdminController {

  static $page;
  static $itemsPager = 5;

  public function index()
  {
    $this->twig->display('main_tpl' , $this->data );
  }

  public function addnews()
  {
    if($this->user->has_permission('news') || $this->user->has_permission('admin') )
    {

      $this->load->model('news_modal');
      $this->titleSet('Create news');
      $this->load->library('form_validation');

      $this->form_validation->set_rules('title', 'news title', 'required');
      $this->form_validation->set_rules('newstext' , 'news content', 'required');

      $field = array();

      if ($this->form_validation->run() == FALSE)
      {
        $this->data['message'] = writeMessage( validation_errors() , 'error');
        $field['title'] = set_value('title');
        $field['newstext'] = set_value('newstext');
      }
      else
      {
        $time = time();
        $POST = $_POST;
        $query = $this->news_modal->create( $POST['title'] , $POST['newstext'] , $time );
        if ( $query )
        {
          $this->data['message'] = writeMessage( "News successfully created!" , "status");
        }
        else
        {
          $this->data['message'] = writeMessage("Something wrong please trt again or contact system administrator" , "error");
        }
      }
      $this->data['content'] = $this->twig->render('panel/create_news' , $field);
    }
    else
    {
      $this->data['content'] = "<p>You don't have permission to access this content</p>";
    }

    $this->index();
  }

  public function newsdelete( $i = 0 )
  {
    if($this->user->has_permission('news') || $this->user->has_permission('admin') )
    {
      $this->load->library('pagination');
      $this->load->model('news_modal');

      $this->data['pagetitle'] = "News edit";

      $query_pagination['limit']['start'] = $i;
      $query_pagination['limit']['count'] = self::$itemsPager;

      $news['content'] = $this->news_modal->read( $query_pagination );

      $qp['numrow'] = true;

      $config = $this->pagerConf( $this->news_modal->read( $qp ) );

      $this->pagination->initialize($config);
      $news['pagination'] = $this->pagination->create_links();
      $news['delete_permision'] = true;
      $this->data['content'] = $this->twig->render('news_list', $news);
    }
    else
    {
      $this->data['content'] = "<p>You don't have permission to access this content</p>";
    }

    $this->index();
  }

  public function news_delete($id)
  {
    $id = intval($id);

    if( $id == 0 ){
      $this->data['message'] = writeMessage( "undefined news id" , "status");
      $this->index();
      return false;
    }

    if( $this->user->has_permission('news') || $this->user->has_permission('admin') )
    {
      $this->load->model('news_modal');
      $q = $this->news_modal->delete($id);
      if($q)
      {
        $this->data['message'] = writeMessage("News successfully deleted","status");
      }
      else
      {
        $this->data['message'] = writeMessage("Something wrong please try again" , "error");
      }
    }
    else
    {
      $this->data['content'] = "<p>You don't have permission to access this content</p>";
    }

    $this->index();

  }

  public function logout()
  {
    $this->user->destroy_user('/');
  }

  private function pagerConf( $page )
  {
    $config['base_url'] = '/panel/newsdelete';
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