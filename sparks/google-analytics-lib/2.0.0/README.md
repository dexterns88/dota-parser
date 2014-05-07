# Google-analytics-lib integration of google analitics for codeigniter

## Requirements

* CodeIgniter 2
* [CodeIgniter Sparks](http://getsparks.org/)

## Setup

1. [Install Sparks](http://getsparks.org/install) if you haven't done so already.
2. Install the google-analytics-lib spark (see [here](http://getsparks.org/get-sparks) if you don't know how).

## Info

google-analytics-lib is a spark that allows to include google analitics on your project in production mode.

## Usage

controler call

## $this->load->spark('google-analytics-lib/x.x.x');


To load the google-analytics-lib spark..



## google analytics-lib 1.

#### incluede:

	config-> autoload.php
	config-> config.php
	libraries->analytics.php

#### Setup:

In config file setup source where your js for analitics is stored

$config['source']	= '/theme/js/ganalitics.js';

Into your views file in header you need to seample call global variable ganalitics.

if( defined('ganalytics') ) {
  print( ganalytics );
}

When ENVIRONMENT setup to production your google analytics will be include into the page.
