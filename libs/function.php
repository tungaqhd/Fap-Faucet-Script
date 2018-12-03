<?php
function random_key($length) {
	$str = "";
	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
	$size = strlen( $chars );
	for( $i = 0; $i < $length; $i++ ) {
		$str .= $chars[ rand( 0, $size - 1 ) ];
	}
	return $str;
}

function check_ip($ip) {
	global $mysqli,$settings;
	$check = $mysqli->query("SELECT * FROM ip_list WHERE ip_address = '$ip'");
	if ($check->num_rows == 1) {
		$data = $check->fetch_assoc();
		$time = $data['last_claim'] + $settings['timer'] - time();
		if ($time > 0) {
			return $time;
		} else {
			return 'ok';
		}
	} else {
		$mysqli->query("INSERT INTO ip_list (ip_address, last_claim) VALUES ('$ip', '0')");
		return 'ok';
	}
}

function check_address($address) {
	global $mysqli,$settings,$ref;
	$check = $mysqli->query("SELECT * FROM address_list WHERE address = '$address'");
	if ($check->num_rows == 1) {
		$data = $check->fetch_assoc();
		$time = $data['last_claim'] + $settings['timer'] - time();
		if ($time > 0) {
			return $time;
		} else {
			return 'ok';
		}
	} else {
		$mysqli->query("INSERT INTO address_list (address, referred_by, last_claim, status) VALUES ('$address', '$ref', '0', '0')");
		return 'ok';
	}
}

function claim_success($address, $ip) {
	global $mysqli;
	$time = time();
	$mysqli->query("UPDATE ip_list SET last_claim = '$time' WHERE ip_address = '$ip'");
	$mysqli->query("UPDATE address_list SET last_claim = '$time' WHERE address = '$address'");
	$mysqli->query("DELETE FROM failure WHERE address = '$address' AND ip_address = '$ip'");
}

function alert($type, $content) {
	switch ($type) {
		case 'send':
		return $content;
		break;
		case 'success':
		return '<div class="alert alert-success text-center"><i class="far fa-check-circle"></i> ' .$content. '</div>';
		break;
		case 'danger':
		return '<div class="alert alert-danger text-center"><i class="fas fa-exclamation-triangle"></i> ' .$content. '</div>';
		break;
	}
}

function failure($address) {
	global $mysqli, $ip;
	$check = $mysqli->query("SELECT * FROM failure WHERE address = '$address'")->num_rows;
	if ($check < 4) {
		$mysqli->query("INSERT INTO failure (address, ip_address) VALUES ('$address', '$ip')");
	} else {
		$check = $mysqli->query("SELECT * FROM failure WHERE ip_address = '$ip'")->num_rows;
		if ($check < 4) {
			$mysqli->query("INSERT INTO failure (address, ip_address) VALUES ('$address', '$ip')");
		}
	}
}

function check_failure($address) {
	global $mysqli,$ip;
	$check = $mysqli->query("SELECT * FROM failure WHERE address = '$address'")->num_rows;
	if ($check >= 4) {
		return true;
	} else {
		$check = $mysqli->query("SELECT * FROM failure WHERE ip_address = '$ip'")->num_rows;
		if ($check >= 4) {
			return true;
		}
	}
	return false;
}

function recaptcha($response, $settings) {
	$Captcha_url = 'https://www.google.com/recaptcha/api/siteverify';
	$Captcha_data = array('secret' => $settings['recaptcha_secret_key'], 'response' => $response);
	$Captcha_options = array(
		'http' => array(
			'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
			'method'  => 'POST',
			'content' => http_build_query($Captcha_data),
		),
	);
	$Captcha_context  = stream_context_create($Captcha_options);
	$Captcha_result = file_get_contents($Captcha_url, false, $Captcha_context);
	if (!json_decode($Captcha_result)->success) {
		return false;
	}
	return true;
}

function solvemedia($adcopy_challenge, $adcopy_response, $settings) {
	include 'solvemedia.php';
	$solvemedia_response = solvemedia_check_answer($settings['solvemedia_private_key'], $this->input->ip_address(), $adcopy_challenge, $adcopy_response, $settings['solvemedia_hash_key']);
	if (!$solvemedia_response->is_valid) {
		return false;
	}
	return true;
}

function getlink($address, $sec_key, $currency, $reward) {
	global $mysqli,$ip,$settings,$link,$default_link;
	$time = time();
	$mysqli->query("DELETE FROM link WHERE address = '$address'");
	$mysqli->query("INSERT INTO link (address, sec_key, time_created, currency, reward) VALUES ('$address', '$sec_key', '$time', '$currency', '$reward')");
	foreach ($link as $key => $value) {
		if (!isset($_COOKIE[$key])) {
			$api_url = $value;
			setcookie($key, 'fuck cheater :P', time() + 86400);
			break;
		}
	}
	if (!isset($api_url)) {
		$api_url = $default_link;
	}
	$url = $url = $settings['url']. 'back.php?k={key}';
	$url = str_replace('{key}', $sec_key, $url);
	$long_url = urlencode($url);
	$api_url = str_replace('{url}', $url, $api_url);
	$get_link = @json_decode(file_get_contents($api_url),TRUE);
	if ($get_link['status'] == 'success') {
		return $get_link['shortenedUrl'];
	}
	return 123;
}