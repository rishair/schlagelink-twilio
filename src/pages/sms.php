<?

$from = user(p('From'));

$message = '';
if ($from) {
	$schlage = Schlage::GetInstance();
	$schlage->Login();
	$locks = $schlage->GetLocks();
	$schlage->Activate($locks[0]);
	log_action($from, 'Activated Front Door [SMS]');
	incr_stat('authorized');
	incr_stat('authorized_sms');
} else {
	if (p('From')) {
		log_action(p('From'), 'Unauthorized [SMS]');
		incr_stat('unauthorized');
		incr_stat('unauthorized_sms');
	}
}

?>