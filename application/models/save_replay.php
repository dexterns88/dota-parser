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

  function view_all( $mod  , $qPagination )
  {

    $item = "title, date , saver , link";

    if( $mod == "limit" ){
      $limit = "LIMIT {$qPagination['page']}, {$qPagination['item_per_page']}";
      $query = $this->db->query("SELECT {$item} from games_replay ORDER BY date DESC {$limit}");
    } elseif( $mod == "numrow" ) {
      $query = $this->db->query("SELECT {$item} from games_replay");
    }

    $numQuery = $query->num_rows();

    if( $mod == "numrow" ) {
      return $numQuery;
    }

    if ( $numQuery > 0)
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