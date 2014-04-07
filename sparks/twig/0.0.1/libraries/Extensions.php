<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Extensions {
  
  public function exFunction() {
		
    return array(
      new Twig_SimpleFunction('krumo', 'krumo')
    );
  
  }
  
  public function exFilter() {
		
		return array(
			
		);
		
	}
  
}
