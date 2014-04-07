<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
| Twig config file
*/

/*
| Tempalte directory for twig view file
*/
  $config['template_dir'] = APPPATH.'views';


/*
| Config directory
| $config['cache_dir'] = BASEPATH.'cache/twig';
*/
  $config['cache_dir'] = APPPATH.'cache/twig';
  
/*
| Twig third party aplication path
*/
  $config['twig_App'] = '../vendor';

/*
| Enable or disable cache for twig file
| Recommendt to turn on twig cache for improte performance of application
*/
  $config['cache'] = false;
  
/*
| Debug mode
*/
  $config['debug'] = true;
  //$config['debug'] = false;
  
/*
| Twig extension
*/
	$config['twigEx'] = 'twig';
