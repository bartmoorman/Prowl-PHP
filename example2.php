#!/usr/bin/php
<?php
require_once('prowl.php');

$prowl = new Prowl();

$shortopts = 'k:a:e:d:u:p';
$longopts = array('apikey:', 'application:', 'event:', 'description:', 'url:', 'priority:');
$options = getopt($shortopts, $longopts);

foreach($options as $k => $v):
	switch($k):
		case 'k':
		case 'apikey':
			$prowl->addApiKey($v);
			break;
		case 'a':
		case 'application':
			$prowl->setApplication($v);
			break;
		case 'e':
		case 'event':
			$prowl->setEvent($v);
			break;
		case 'd':
		case 'description':
			$prowl->setDescription($v);
			break;
		case 'u':
		case 'url':
			$prowl->setUrl($v);
			break;
		case 'p':
		case 'priority':
			$prowl->setPriority($v);
			break;
	endswitch;
endforeach;

$prowl->send();
?>
