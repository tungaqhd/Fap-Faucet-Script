<?php
# need help -> https://fapcoin.cc/threads/fap-faucet-script-multi-coin-short-link-antibotlinnks.2/
#DATABASE SETTINGS
$hostname = 'localhost';
$db_username = '';
$db_password = '';
$db_name = '';

#SITE SETTINGS
$settings['name'] = 'Fap Faucet'; // Your faucet name
$settings['description'] = 'Free Bitcoin'; // Your faucet description
$settings['url'] = 'http://[::1]/'; // Your faucet homepage url (end with /)
$settings['status'] = 'on'; // Turn on or off faucet

#PAYOUT SETTINGS
$settings['payout'] = 'BTC-50|ETH-500|DOGE-50000'; // Payout option, format: <coin1>-<reward1>|<coin2>-<reward2>|<coin3>-<reward3>.....
$settings['timer'] = 300; // Time to wait in seconds
$settings['referral_comission'] = '15';
$settings['api'] = ''; // Your FaucetHub Api

#SECURITY SETTINGS
$settings['captcha'] = 'solvemedia'; // recaptcha Or solvemedia

$settings['recaptcha_public_key'] = '';
$settings['recaptcha_secret_key'] = '';

$settings['solvemedia_challenge_key'] = '';
$settings['solvemedia_private_key'] = '';
$settings['solvemedia_hash_key'] = '';

$settings['iphub'] = 'MzY4NDpLWXpHSFZXcUk3REJSaEx0U0NPNUhyNEJBRWNSWG1jTA=='; // Use IpHub to block bad ips

#ADVERTISEMENT SETTINGS
$settings['top_ad'] = '<img src="http://placehold.it/728x90">';
$settings['left_ad'] = '<img src="http://placehold.it/160x600">';
$settings['right_ad'] = '<img src="http://placehold.it/160x600">';
$settings['bottom_ad'] = '<img src="http://placehold.it/300x250">';
$settings['modal_ad'] = '<img src="http://placehold.it/428x90">';
$settings['footer_ad'] = '<img src="http://placehold.it/728x90">';

$settings['short_link'] = 'off';
$link[1] = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';
$link[2] = 'https://123link.co/api?api=767c9859badd2f829f1f363d51e8c18033a84af9&url={url}';
$link[3] = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';
$link[4] = 'https://123link.co/api?api=767c9859badd2f829f1f363d51e8c18033a84af9&url={url}';
$link[5] = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';
$link[6] = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';
$link[7] = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';
$link[8] = 'https://123link.co/api?api=767c9859badd2f829f1f363d51e8c18033a84af9&url={url}';
$default_link = 'http://btc.ms/api/?api=86b6c147ce28028e5c7762afce1656f898279889&url={url}';