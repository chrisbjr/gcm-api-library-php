<?php

require('Gcm.php');

$gcm_registration_id = 'APA91bEHyu7V8zsmMFJ2pBnRppUZd7sd4XLiZdLa1c0_djjMsaWh3IUVMrum5sE9Is7ZFqmbYJ71B9ovbzdpcN2Tk-mFnphFCIYVw3sLvExboL7HENbz9rZOjNH2Co2WnzVBdEwhGHTdGbydb3xJiDIOG_n33kSlJKGQGATWgOYfepYOn6Z2egk';

// or you can also use an array

//$gcm_registration_id = array(
//	'xxxx',
//	'xxxx',
//);

$gcm = new Gcm();

$title = 'Sample title';
$message = 'Sample messasge';

if ($gcm->send_notification($gcm_registration_id, $title, $message) === TRUE)
{
	// sent
}
else
{
	// failed
}

// Check the response here:
print_r($gcm->get_response());