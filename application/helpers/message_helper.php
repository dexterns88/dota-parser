<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 5/09/14
 * Time: 11:10 PM
 */


function writeMessage($message , $type = "warning")
{
  $type = strtolower($type);

  if( $type != 'error' && $type != 'status' && $type != 'warning' )
  {
    show_error('wrong message type' , 500 );
    die();
  }

  return "<div class='message {$type}'>{$message}</div>";

}