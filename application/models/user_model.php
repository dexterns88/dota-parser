<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 5/12/14
 * Time: 11:06 PM
 */

class User_model extends Ci_Model {

  function __constructor()
  {
    $this->load->database();
    parent::__construct();
  }

  function getUserInfo()
  {

    $row = " id,name,login,active,email";
    $sql = "SELECT {$row} from users";

    $query = $this->db->query($sql);

    if( $query->num_rows > 0 )
    {
      $out = array();
      foreach( $query->result() as $row )
      {
        array_push( $out , $row );
      }
    }
    else
      $out = false;

    $this->db->close();

    return $out;

  }

}