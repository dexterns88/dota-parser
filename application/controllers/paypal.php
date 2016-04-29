<?php if( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: Dexter
 * Date: 29/04/2016
 * Time: 20:34
 */

class Paypal extends MY_Controller {
  
  public function index()
  {
    $this->data['body_class'] = "donation success_paypal";
    $this->data['title'] = 'Thanks for donation / w3xSilverCloud';

    $this->data['pagetitle'] = "Donation";

    $this->data['content'] = $this->twig->render('paypal' );

    $this->twig->display('main_tpl' , $this->data );
  }

}