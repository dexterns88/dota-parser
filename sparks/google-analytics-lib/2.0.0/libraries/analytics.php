<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Analytics {
	
	private $ci;    // for CodeIgniter Super Global Reference.
	private $path;
	
	
	public function __construct()
	{
		$this->ci =& get_instance();
		$this->ci->config->load('config');
		$this-> path = $this->ci->config->item('source');
		$this->analitic();
  }
  
  private function analitic()
  {
		if (defined('ENVIRONMENT') )
		{
			if( ENVIRONMENT != 'development' )
			{
				
				$analitic = $this->path;
				$rootpath = $this->ci->config->item('pubic_html');
				$rootanalitic = BASEPATH . "../" . $rootpath . "/" . $analitic;
				
				if( file_exists($rootanalitic) )
				{
					$script = "<script type='text/javascript' src='{$analitic}'></script>";
					define('ganalytics', $script );
				}
				
			}
		}
		else
		{
			show_error("ENVIRONMENT undefined");
		}
		
	}
	
}
