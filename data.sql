
CREATE TABLE `address_list` (
  `id` int(32) UNSIGNED NOT NULL,
  `address` varchar(75) NOT NULL,
  `referred_by` varchar(75) NOT NULL,
  `last_claim` int(32) NOT NULL,
  `status` varchar(10) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `failure` (
  `address` varchar(60) NOT NULL,
  `ip_address` varchar(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

CREATE TABLE `ip_list` (
  `id` int(32) UNSIGNED NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `last_claim` int(32) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;


CREATE TABLE `link` (
  `address` varchar(50) NOT NULL,
  `sec_key` varchar(50) NOT NULL,
  `time_created` varchar(20) NOT NULL,
  `currency` varchar(10) NOT NULL,
  `reward` int(20) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

ALTER TABLE `address_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `ip_list`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `address_list`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;


ALTER TABLE `ip_list`
  MODIFY `id` int(32) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;
