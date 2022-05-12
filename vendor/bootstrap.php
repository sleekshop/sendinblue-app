<?php
/*
* root file for connecting with the sleekshop - backend
* version: 1.0.0
* (c) Demo app - Manisha Sharma
*/

// Load our autoloader
require_once __DIR__.'/autoload.php';
require_once __DIR__.'/config.php';
require_once __DIR__.'/sleekcommerce/sleekshop_request.inc.php';

// Specify our Twig templates location
$loader = new Twig_Loader_Filesystem(__DIR__.'/..'.'/templates');

 // Instantiate our Twig
$twig = new Twig_Environment($loader);
