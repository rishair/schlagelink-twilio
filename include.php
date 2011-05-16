<?

date_default_timezone_set('America/New_York');

include "config.php";

foreach(glob("src/helpers/*") as $file) {
	include $file;
}

// Extract phone number
function number	($phone)		{ return substr(preg_replace('/[^0-9]/', '', $phone), -10); }

// Get post variable if it exists
function p($k) 					{ return isset($_POST[$k]) ? $_POST[$k] : NULL; }

// Get session variable if it exists or set session variable if $v is provided
function s($k, $v = NULL) 		{ if ($v === NULL) return isset($_SESSION[$k]) ? $_SESSION[$k] : NULL; $_SESSION[$k] = $v;}

// Redirect to a URL
function r($u) 					{ header('Location: ' . $u); die(); }

// Get element from array
function o($o, $k)				{ return isset($o[$k]) ? $o[$k] : ''; }

// Get redis instance
function redis() 				{ return Predis_Client::GetInstance(); }

// Get user array
function user($phone) { 
	if (strpos($phone, '@')) {
		$phone = redis()->hget('gmails', array_shift(explode('/', $phone)));
	}
	return json_decode(redis()->hget('users', number($phone)), true);
}

?>
