<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Welcome extends MY_Controller {

	public function index()
	{

    $this->load->model( array('save_replay' , 'news_modal' ) );

  //home page view
    $query_pagination['page'] = 0;
    $query_pagination['item_per_page'] = 5;

    $query['type'] = "block";

    $query['lists'] = $this->save_replay->view_all( 'limit', $query_pagination );

    $news['config']['limit']['count'] = 5;
    $news['config']['limit']['start'] = 0;
    $news['type'] = 'block';

    $news['content'] = $this->news_modal->read( $news['config'] );

    $news['title'] = "Latest news";

    $query['title'] = "Latest saved game";

    $this->data['content'] = $this->twig->render('list_view' , $query );

    $this->data['content'] .= $this->twig->render('news_list' , $news );

    $this->twig->display('main_tpl' , $this->data );
	}
}
