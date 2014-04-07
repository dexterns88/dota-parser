<?php

class ci_twig_extension {
  
  public function exFunction() {
    return array(
      new Twig_SimpleFunction('krumo', 'krumo')
    );
  }
  
}
