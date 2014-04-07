<?php if (!defined('BASEPATH')) {exit('No direct script access allowed');}

/* 
|  Twig library
|  Version 1.0
|  Relese date: 16.5.2013.
|=================================================================================================
*/

class Twig
{
	private $CI;
	private $_twig;
	private $_template_dir;
	private $_cache_dir;
  
  private $_cache; // enable disable cache
  private $_twig_conf = array(); // twig_conf
  private $_debug;
  private $_twig_App;
  private $_twig_extension;
  private $_extends;

	/**
	 * Constructor
	 *
	 */
	function __construct($debug = false)
	{
    $this->CI =& get_instance();
	  $this->CI->config->load('twig');
	  //ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . APPPATH . 'libraries/Twig');
    
    $this->_twig_App = $this->CI->config->item('twig_App');
    
    $extmp = $this->CI->config->item('twigEx');
    if( $extmp ) {	
			$this->_twig_extension = "." . $extmp;
		} else {
			$this->_twig_extension = ".twig";
		}
		
    require_once (string) $this->_twig_App . '/autoload' . EXT;
            
    log_message('debug', "Twig Autoloader Loaded");

    Twig_Autoloader::register();

    $this->_template_dir = $this->CI->config->item('template_dir');
    $this->_cache_dir = $this->CI->config->item('cache_dir');
    $this->_cache = $this->CI->config->item('cache');
    $this->_debug = $this->CI->config->item('debug');

    $loader = new Twig_Loader_Filesystem($this->_template_dir);

    if( $this->_cache )
    {
      $config['cache'] = $this->_cache_dir;
    }

    if( $this->_debug )
    {
      $config['debug'] = $this->_debug;
    } else {
      $config['debug'] = false;
    }

    $this->_twig = new Twig_Environment($loader, $config );
    $this->_twig->addExtension(new Twig_Extension_Debug() );
    
    $this->extend();
    
	}
	
	private function extend()
	{
		require_once __DIR__ . "/Extensions.php";
		$this->_extends = new Extensions();
		$this->extendFunction();
	}
	
	private function extendFilter()
	{
		$extend['filter'] = $this->_extends->exFilter();
		foreach( $extend['filter'] as $filter )
		{
			$this->_twig->addFilter( $filter );
		}
	}
	
	private function extendFunction()
	{
    $extend['function'] = $this->_extends->exFunction();
    foreach( $extend['function'] as $function )
    {
			$this->_twig->addFunction( $function );
		}
	}
	
	public function render($template, $data = array()) 
  {
		$template = $template . $this->_twig_extension;
    $template = $this->_twig->loadTemplate($template);
    return $template->render($data);
	}
  
  public function display($template, $data = array())
  {
    $template = $template . $this->_twig_extension;
    $template = $this->_twig->loadTemplate($template);
    
    /* elapsed_time and memory_usage */
    $data['elapsed_time'] = $this->CI->benchmark->elapsed_time('total_execution_time_start', 'total_execution_time_end');
    $memory = (!function_exists('memory_get_usage')) ? '0' : round(memory_get_usage()/1024/1024, 2) . 'MB';
    $data['memory_usage'] = $memory;

    $template->display($data);
	}
  
} // end class

?>
