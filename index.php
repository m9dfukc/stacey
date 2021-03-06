<?php

# $_GET stringfix ... the global get array can't handle dots in the key
$F_GET = (!empty($_SERVER["argv"][0])) ? array($_SERVER["argv"][0] => "") : array();

# let people know if they are running an unsupported version of PHP
if(phpversion() < 5) {
  
  die('<h3>Stacey requires PHP/5.0 or higher.<br>You are currently running PHP/'.phpversion().'.</h3><p>You should contact your host to see if they can upgrade your version of PHP.</p>');

} else {

  # require helpers class so we can use rglob
  require_once './app/helpers.inc.php';
  # include any php files which sit in the app folder
  foreach(Helpers::rglob('./app/**.inc.php') as $include) include_once $include;
  # include custom codeignitor (http://codeigniter.com/user_guide/index.html) 
  # helpers which sit in the app/helpers folder
  define(BASEPATH, "");
  foreach(Helpers::rglob('./app/helpers/**_helper.php') as $include) include_once $include;
  
  # start the app
  new Stacey($F_GET); // new Stacey($_GET);
  
}

?>