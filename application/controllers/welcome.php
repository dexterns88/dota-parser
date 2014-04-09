<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//class Welcome extends CI_Controller {
class Welcome extends MY_Controller {

	public function index()
	{
    $this->twig->display('main_tpl' , $this->data );
	}
}
