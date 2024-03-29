<?php
/**
 * Created by PhpStorm.
 * User: dexter
 * Date: 4/23/14
 * Time: 8:33 PM
 */

class Download extends My_Controller { //CI_Controller {

  private $file;

  public function index()
  {
    show_404();
  }

  public function replay( $files = false )
  {

    if( $files == false )
    {
      show_404();
    }

    set_time_limit(0);
    ignore_user_abort(false);
    ini_set('output_buffering', 0);
    ini_set('zlib.output_compression', 0);

    $this->file = $this->storage . $files;

    if ( file_exists($this->file) )
    {
      header('Content-Description: File Transfer');
      header('Content-Type:application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($this->file));
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header("Content-Transfer-Encoding: binary\n");
      header('Pragma: public');
      header('Content-Length: ' . filesize($this->file));
      header('Connection:close');
     
      ob_clean();
      flush();
      readfile($this->file);

      exit;
    } else {
      $this->data['content'] = "<h1>Not found</h1>";
      $this->data['content'] .= "<h3>This file {$files} doesn't exist or it's deleted </h3>";
      $this->twig->display('main_tpl', $this->data );
    }

  }

}