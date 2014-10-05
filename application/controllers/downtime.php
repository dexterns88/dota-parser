<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Downtime extends MY_Controller {

  public function index()
  {
    array_push( $this->data['styles'] , "http://fonts.googleapis.com/css?family=Covered+By+Your+Grace" );

    $this->load->model( array('save_replay' , 'news_modal' ) );

    $this->data['keywords'] = ',dota home';
    $this->data['title'] = 'HOME / w3xSilverCloud';


    $news['title'] = "Latest news";

    $query['title'] = "Latest saved game";


    $this->twig->display('downtime' , $this->data );
  }
}
