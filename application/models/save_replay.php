<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 4/12/14
 * Time: 6:11 PM
 */

class Save_replay extends CI_Model {

  var $value , $sql , $result , $out;

  function __construct()
  {
    $this->load->database();
    parent::__construct();
  }

  function insert( $data )
  {
    $row = "title , date , saver , link";
    $value = " {$this->db->escape($data['title'])} , '{$data['time']}' , '{$data['saver']}' , '{$data['link']}' ";
    $sql = "INSERT INTO games_replay( {$row} ) VALUES( {$value} )";
    $result = $this->db->query( $sql );
    return $result;
  }

  function view_all()
  {
    $item = "title, date , saver , link";
    $query = $this->db->query("SELECT {$item} from games_replay");

    if ($query->num_rows() > 0)
    {
      $out = array();
      foreach ($query->result() as $row)
      {
        array_push( $out , $row );
      }
    } else {
      $out = false;
    }
    return $out;

  }

}