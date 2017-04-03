<?php
//This example is useful for integrating with other scripts
require_once('inc/prowl.inc');

$prowl = new Prowl();

$prowl->addApiKey('xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx');
$prowl->addApiKey('yyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyyy');
$prowl->setApplication('My Application');
$prowl->setEvent('New Event');
$prowl->setDescription('Notification Text');
$prowl->setUrl('http://www.google.com/');
$prowl->setPriority(-1);

$prowl->send();
?>
