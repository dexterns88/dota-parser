<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

include_once "base.php";

//class Welcome extends CI_Controller {
class Welcome extends Base {

	public function index()
	{
    $this->twig->display('main_tpl' , $this->data );
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */