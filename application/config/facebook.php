<?php

return array(

	// you can put the values directly in here
	// this is just to keep them secret from github

	'url'        => 'https://www.facebook.com/WinnipegScrabble',
	'appId'      => array_get($_SERVER, 'FACEBOOK_APPID'),
	'secret'     => array_get($_SERVER, 'FACEBOOK_SECRET'),
	'fileUpload' => false,
	'uid'        => array_get($_SERVER, 'FACEBOOK_UID'),

);
