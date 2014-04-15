<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

class Extensions {
  
  public function exFunction() {
		
    return array(
      new Twig_SimpleFunction('krumo', 'krumo'),
      new Twig_SimpleFunction('sprintf' , 'sprintf'),
      new Twig_SimpleFunction('convert_time','convert_time')
    );
  
  }
  
  public function exFilter() {
		
		return array(
			
		);
		
	}
  
}
