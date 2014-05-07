<?php

echo 'Clearing template cache...';

basePath();

switch( $argv[2] ) {
  case 'all':
    clearCache();
    break;
  case 'rebuild':
    rebuildtheme();
    break;
  default:
    commandList();
  
}

function rebuildtheme() {
  include_once('config.php');
  $handle = curl_init();
  curl_setopt( $handle, CURLOPT_URL, $config['weburl'] );
  curl_setopt( $handle, CURLOPT_HEADER, false );
  curl_exec( $handle );
  curl_close( $handle );
  
  echo "rebuilded";
}

function clearCache() {
  include_once('config.php');
  $path = $config['path'];
  deleteDir( $path );
}

function basePath() {
  define('BASEPATH', str_replace("\\", "/"));
}

function deleteDir($dirPath) {
  
  $emptycheck = "\n Unchanged -> Cache already cleared!";
  $dir = $dirPath;
  $it = new RecursiveDirectoryIterator($dir);
  $files = new RecursiveIteratorIterator($it,
               RecursiveIteratorIterator::CHILD_FIRST);
  echo "\n";
    //echo "\n unchanged -> Cache already cleared!";
  foreach($files as $file) {
    if ($file->getFilename() === '.' || $file->getFilename() === '..') {
      continue;
    }
    if ($file->isDir()){
      echo "\n success -> {$file}";
      rmdir($file->getRealPath());
    } else {
      echo "\n success -> {$file}";
      unlink($file->getRealPath());
    }
    $emptycheck = "\n \nAll Caches cleared.";
  }
  echo $emptycheck;
  
}

function commandList() {
  $print = "\n Wrong command call. Supporet list";
  $print .= "\n\n  lex cc all -> clear all twig cache";
  $print .= "\n  lex cc rebuild -> rebuild theme and prepare cache file.";
  
  echo $print;
}
