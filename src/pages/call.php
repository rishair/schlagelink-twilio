<? echo '<?xml version="1.0" encoding="UTF-8" ?> '; ?> <?

$from = user(p('From'));
$message = '';
if (!$from) {
	$message = 'Who do you think you are?';
	log_action(p('From'), 'Unauthorized [DIAL]');
	incr_stat('unauthorized');
	incr_stat('unauthorized_dial');
} else {
	$user = explode(' ', $from['name']);
	$user = $user[0];
	$message = 'Welcome back ' . $user;
	$schlage = Schlage::GetInstance();
	$schlage->Login();
	$locks = $schlage->GetLocks();
	$schlage->Activate($locks[0]);
	log_action($from, 'Activated Front Door [DIAL]');
	incr_stat('authorized');
	incr_stat('authorized_dial');
}

?>

<Response> 
    <Say voice="man"><?=$message?></Say>
</Response>