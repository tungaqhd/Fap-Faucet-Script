<h1>Remember to remove this file when you are done :)</h1>
<?php
include 'libs/core.php';
if (!empty($hostname) and !empty($db_username) and !empty($db_password) and !empty($db_name)) {
	$mysqli->query("CREATE TABLE `address_list` (
		`id` int(32) UNSIGNED NOT NULL,
		`address` varchar(75) NOT NULL,
		`referred_by` varchar(75) NOT NULL,
		`last_claim` int(32) NOT NULL,
		`status` varchar(10) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	$mysqli->query("CREATE TABLE `failure` (
		`address` varchar(60) NOT NULL,
		`ip_address` varchar(20) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	$mysqli->query("CREATE TABLE `ip_list` (
		`id` int(32) UNSIGNED NOT NULL,
		`ip_address` varchar(50) NOT NULL,
		`last_claim` int(32) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	$mysqli->query("CREATE TABLE `link` (
		`address` varchar(50) NOT NULL,
		`sec_key` varchar(50) NOT NULL,
		`time_created` varchar(20) NOT NULL,
		`currency` varchar(10) NOT NULL,
		`reward` int(20) NOT NULL
	) ENGINE=MyISAM DEFAULT CHARSET=latin1;");
	$mysqli->query("ALTER TABLE `address_list`
		ADD PRIMARY KEY (`id`);");
	$mysqli->query("ALTER TABLE `ip_list`
		ADD PRIMARY KEY (`id`);");
	$mysqli->query("ALTER TABLE `address_list`
		MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");
	$mysqli->query("ALTER TABLE `ip_list`
		MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;");

	echo "Please delete me :)";
}
?>