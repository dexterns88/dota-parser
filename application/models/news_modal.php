<?php

class News_modal extends CI_Model {

  var $limit , $out;

  function __construct()
  {
    $this->load->database();
    parent::__construct();
  }

  function read( $settings = false )
  {

    if( isset($settings['limit']))
    {
      $limit = "LIMIT {$settings['limit']['start']} , {$settings['limit']['count']}";
    }

    $row = "id , date , text , title";

    $sql = "SELECT {$row} from news {$limit}";

    $query = $this->db->query($sql);

    if ( $query->num_rows > 0 )
    {
      $out = array();
      foreach( $query->result() as $row)
      {
        array_push( $out , $row);
      }

    }
    else $out = false;

    return $out;
  }

}