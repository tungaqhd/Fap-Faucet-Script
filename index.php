<?php
include 'libs/core.php';

if (isset($_GET['r']) and !isset($_POST['claim'])) {
	setcookie('r', $_GET['r'], time()+864000);
}
#CHECK FAUCET STATUS
if ($settings['status'] == 'off') {
	$error = alert('danger', 'Faucet Is Disabled');
}

#GET PAYOUT
$currency_list = explode('|', $settings['payout']);

#SELECT CAPTCHA
switch ($settings['captcha']) {
	case 'recaptcha':
	$captcha = '<div class="g-recaptcha" data-sitekey="' .$settings['recaptcha_public_key']. '"></div><script src="https://www.google.com/recaptcha/api.js"></script>';
	break;

	case 'solvemedia':
	include 'libs/solvemedia.php';
	$captcha = solvemedia_get_html($settings['solvemedia_challenge_key']);
	break;

	default:
	$captcha = alert('danger', 'Invalid Captcha Setting');
	$error = '';
	break;
}

#CHECK IP
$ip_status = check_ip($ip);
if ($ip_status !== 'ok') {
	$error = "";
	$wait = '<h3 class="text-center">You Have To Wait:</h3>
	<center><div id="CountDownTimer" data-timer="' .$ip_status. '" style="width: 60%;margin: 0px auto;"></div></center>';
}

if (isset($_POST['claim'], $_POST['address'], $_POST['token'], $_SESSION['token'], $_SESSION['antibotlinks']['solution']) and $ip_status == 'ok' and !isset($error)) {

	$address = mysqli_real_escape_string($mysqli,preg_replace("/[^ \w]+/", "",trim($_POST['address'])));

	#VALIDATE ADDRESS
	if (strlen($address) < 10 or strlen($address) > 60) {
		$error = alert('danger', 'Invalid Address');
	}

	#CHECK SESSION TOKEN
	if (!isset($error) and $_POST['token'] !== $_SESSION['token']) {
		$error = alert('danger', 'Invalid Session Key');
		failure($address);
	}

	#CHECK ANTIBOTLINKS
	if (!isset($error) and ((trim($_POST['antibotlinks'])!==$_SESSION['antibotlinks']['solution'])or(empty($_SESSION['antibotlinks']['solution'])))) {
		$error = alert('danger', 'Invalid Antibotlinks');
		failure($address);
	}

	#CHECK CAPTCHA
	if (!isset($error)) {
		switch ($settings['captcha']) {
			case 'recaptcha':
			if (isset($_POST['g-recaptcha-response'])) {
				if (!recaptcha($_POST['g-recaptcha-response'], $settings)) {
					$error = alert('danger', 'Invalid Captcha');
					failure($address);
				}
			} else {
				$error = alert('danger', 'Invalid Captcha');
				failure($address);
			}
			break;
			case 'solvemedia':
			if (isset($_POST["adcopy_challenge"],$_POST["adcopy_response"])) {
				$solvemedia_response = solvemedia_check_answer($settings['solvemedia_private_key'],
					$ip,
					$_POST["adcopy_challenge"],
					$_POST["adcopy_response"],
					$settings['solvemedia_hash_key']);
				if (!$solvemedia_response->is_valid) {
					$error = alert('danger', 'Invalid Captcha');
					failure($address);
				}
			} else {
				$error = alert('danger', 'Invalid Captcha');
				failure($address);
			}
			
			break;

			default:
			$error = alert('danger', 'Wrong Captcha Setting');
			break;
		}
	}

	#BAD IP
	if (!isset($error)) {
		$check_iphub = $mysqli->query("SELECT * FROM ip_list WHERE ip_address = '$ip' AND last_claim != '0'")->num_rows;
		if ($check_iphub == 0) {
			include 'libs/iphub/iphub.class.php';
			if (isBadIP($ip, $settings['iphub'])) {
				$error = alert('danger', 'Your IP Address Is Banned By Iphub');
				failure($address);
			}
		}
	}

	# CHECK REF
	if (!isset($error)) {
		$ref = 0;
		if (isset($_COOKIE['r'])) {
			if (strlen($_COOKIE['r']) >=10 and strlen($_COOKIE['r']) <= 60) {
				$ref = mysqli_real_escape_string($mysqli,preg_replace("/[^ \w]+/", "",trim($_COOKIE['r'])));
			}
			setcookie('r', 'lol', time()-3600);
		}
	}

	#CHECK ADDRESS
	$address_status = check_address($address);
	if (!isset($error) and $address_status !== 'ok') {
		failure($address);
		$error = '';
		$wait = '<h3 class="text-center">You Have To Wait:</h3>
		<center><div id="CountDownTimer" data-timer="' .$address_status. '" style="width: 60%;margin: 0px auto;"></div></center>';
	}

	#CHECK IF USER MADE MORE THAN 4 FAILED CLAIM
	if (check_failure($address)) {
		$error = alert('danger', 'You Are Banned');
		failure($address);
	}

	#CLAIM SUCCESS
	if (!isset($error)) {
		setcookie('address', $address, time()+86400);

		$reward = 0;
		$currency = 'BTC';
		foreach ($currency_list as $key => $cu_pa) {
			$reward_display = explode('-', $cu_pa);
			if ($reward_display[0] == $_POST['currency']) {
				$reward = $reward_display[1];
				$currency = $reward_display[0];
			}
		}

		#SHORT LINK CLAIM
		if ($settings['short_link'] == 'on') {
			claim_success($address, $ip);
			$sec_key = random_key(20);
			$l = getlink($address, $sec_key, $currency, $reward);
			echo '<script> window.location.href = "' .$l. '"; </script>';
			header('Location: ' .$l);
			die();
		}

		#NORMAL CLAIM
		include 'libs/faucethub/faucethub.php';
		$faucethub = new FaucetHub($settings['api'], $currency);
		$result = $faucethub->send($address, $reward, $ip);

		if($result["success"] === true) {
			claim_success($address, $ip);
			$ref = $mysqli->query("SELECT * FROM address_list WHERE address = '$address'");
			if ($ref->num_rows == 1) {
				$ref = $ref->fetch_assoc()['referred_by'];
				$amt = $reward/100*$settings['referral_comission'];
				$faucethub->sendReferralEarnings($ref, $amt, $ip);
			}
			$wait = '<h3 class="text-center">You Have To Wait:</h3>
			<center><div id="CountDownTimer" data-timer="' .$settings['timer']. '" style="width: 60%;margin: 0px auto;"></div></center>';
		}
		$error =  $result["html"];
	}
}

# GENERATE SESSION TOKEN
$_SESSION['token'] = random_key(100);

#GET ANTIBOTLINKS
include 'libs/antibot/antibotlinks.php';
$antibotlinks = new antibotlinks(true, 'ttf,otf', array('abl_light_colors'=>'off', 'abl_background'=>'off', 'abl_noise'=>'on'));
$antibotlinks->generate(4, true);
$antibot_js = $antibotlinks->get_js();
$antibot_show_info = $antibotlinks->show_info();

#CHECK SHORT LINK
if (isset($_GET['k']) and !isset($_POST['claim'])) {
	if (strlen($_GET['k']) == 20 and isset($_COOKIE['address'])) {
		$key = mysqli_real_escape_string($mysqli,preg_replace("/[^ \w]+/", "",trim($_GET['k'])));
		$min_time = time() - 300;
		$check = $mysqli->query("SELECT * FROM link WHERE sec_key = '$key' and time_created > '$min_time'");
		if ($check->num_rows == 1) {
			$link = $check->fetch_assoc();
			$address = $link['address'];
			if ($address == $_COOKIE['address']) {
				include 'libs/faucethub/faucethub.php';
				$faucethub = new FaucetHub($settings['api'], $link['currency']);
				$result = $faucethub->send($address, $link['reward'], $ip);
				$error = alert('success', 'Your Keys Are Verified');
				$error .=  $result["html"];

				if($result["success"] === true) {
					$mysqli->query("DELETE FROM link WHERE sec_key = '$key'");
					$ref = $mysqli->query("SELECT * FROM address_list WHERE address = '$address'");
					if ($ref->num_rows == 1) {
						$ref = $ref->fetch_assoc()['referred_by'];
						if ($ref !== '0') {
							$amt = $link['reward']/100*$settings['referral_comission'];
							$faucethub->sendReferralEarnings($ref, $amt, $ip);
						}
					}
				}
			}
		} else {
			$error = alert('danger', 'Invalid or Expried Keys');
			failure(mysqli_real_escape_string($mysqli,preg_replace("/[^ \w]+/", "",trim($_COOKIE['address']))));
		}
	}
}
require_once 'template/main.php';