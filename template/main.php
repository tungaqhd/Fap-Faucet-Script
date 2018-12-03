<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="shortcut icon" href="asset/img/favicon.ico" type="image/x-icon">
	<link rel="icon" href="asset/img/favicon.ico" type="image/x-icon">
	<link rel="stylesheet" href="asset/css/main.css">
	<link rel="stylesheet" href="asset/css/style.css">
	<link rel="stylesheet" href="asset/css/timer.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">

	<title><?=$settings['name']. ' - ' .$settings['description']?></title>
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
		<a class="navbar-brand" href="index.php"><?=$settings['name']?></a>
		<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarColor01" aria-controls="navbarColor01" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarColor01">
			<ul class="navbar-nav mr-auto"></ul>
			<ul class="navbar-nav ml-auto">
				<li class="nav-item active">
					<a class="nav-link" href="index.php"><i class="fas fa-home"></i> Home <span class="sr-only">(current)</span></a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="#faq"><i class="fas fa-book"></i> FAQ</a>
				</li>
			</ul>
		</div>
	</nav>
	<div class="container-fluid">
		<div class="ads">
			<?=$settings['top_ad']?>
		</div>
		<div class="row">
			<div class="col-md-3">
				<div class="ads">
					<img src="http://placehold.it/160x600">
				</div>
			</div>
			<div class="col-md-6">
				<h1 class="text-center brand-name"> <?=$settings['name']?> </h1>
				<?php echo (isset($error)) ? $error : ''; ?>
				<?php if (!isset($wait)) { ?>
				<div class="alert alert-success text-center">Claim <span id="reward"></span> satoshi (<span id="currency"></span>) every <?=floor($settings['timer']/60)?> minutes</div>
					<form action="" method="post">
						<div class="form-group">
							<div class="form-group">
								<div class="input-group mb-3">
									<div class="input-group-prepend">
										<span class="input-group-text"><i class="fas fa-wallet"></i></span>
									</div>
									<input type="text" name="address" class="form-control" placeholder="Enter Your Address" <?php echo (isset($_COOKIE['address'])) ? 'value="' .$_COOKIE["address"]. '"' : ''; ?>>
									<div class="input-group-append">
										<select class="form-control" name="currency" id="select_reward">
											<?php
											foreach ($currency_list as $key => $cu_pa) {
												$reward_display = explode('-', $cu_pa);
												echo '<option value="' .$reward_display[0]. '" data-payout="' .$reward_display[1]. '">' .$reward_display[0]. '</option>';
											}
											?>
										</select>
									</div>
								</div>
							</div>
						</div>
						<input type="hidden" name="token" value="<?=$_SESSION['token']?>" />
						<div class="ads">
							<?=$settings['bottom_ad']?>
						</div>
						<center>
							<?=$captcha?>
						</center>
						<button type="button" class="btn btn-primary btn-lg btn-block claim-button" data-toggle="modal" data-target="#claim">Continue <i class="fas fa-arrow-circle-right"></i></button>
						<div class="modal fade" id="claim" tabindex="-1" role="dialog" aria-labelledby="exampleModalLongTitle" aria-hidden="true">
							<div class="modal-dialog modal-lg" role="document">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalLongTitle">Prove that you are human</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<span aria-hidden="true">&times;</span>
										</button>
									</div>
									<div class="modal-body">
										<?=$antibot_show_info?>
										<div class="antibotlinks"></div>
										<div class="antibotlinks antibotlinks-r"></div>
										<div class="ads">
											<?=$settings['modal_ad']?>
										</div>
										<div class="antibotlinks"></div>
										<div class="antibotlinks antibotlinks-r"></div>
										<button type="submit" name="claim" class="btn btn-success btn-lg btn-block">Claim Your Coin <i class="far fa-check-circle"></i></button>
									</div>
								</div>
							</div>
						</div>
					</form>
				<?php } else { echo $wait; } ?>

				<div class="row ref">
					<div class="col-md-2 ref-icon"><i class="fas fa-users fa-5x"></i></div>
					<div class="col-md-10 ref-content">
						<span class="badge badge-success">Invite your friends and earn <?=$settings['referral_comission']?>% referral comission !</span><br>
						<code><?=$settings['url']?>?r=<?php echo (isset($_COOKIE['address'])) ? $_COOKIE['address'] : 'Your_Address'; ?></code>
					</div>
				</div>
				<hr>
			</div>
			<div class="col-md-3">
				<div class="ads">
					<?=$settings['right_ad']?>
				</div>
			</div>
		</div>
	</div>
	<section id="info1">
		<h4 class="text-center">What is Bitcoin <i class="far fa-question-circle"></i></h4>
		<p>Bitcoin (<i class="fab fa-bitcoin"></i>) is a cryptocurrency, a form of electronic cash. It is the world's first decentralized digital currency, and it was designed to work without a central bank or single administrator.
		Bitcoins are sent from user to user on the peer-to-peer bitcoin network directly, without the need for intermediaries. Transactions are verified by network nodes through cryptography and recorded in a public distributed ledger called a blockchain. Bitcoin was invented by an unknown person or group of people using the name Satoshi Nakamoto and released as open-source software in 2009. Bitcoins are created as a reward for a process known as mining. They can be exchanged for other currencies, products, and services. Research produced by the University of Cambridge estimates that in 2017, there were 2.9 to 5.8 million unique users using a cryptocurrency wallet, most of them using bitcoin.</p>
	</section>
	<section id="info2">
		<h4 class="text-center">What is Bitcoin Faucet <i class="far fa-question-circle"></i></h4>
		<p>A <a href="https://fapcoin.cc/forums/faucet/">bitcoin faucet</a> is a reward system, in the form of a website or app, that dispenses rewards in the form of a satoshi, which is a hundredth of a millionth BTC, for visitors to claim in exchange for completing a captcha or task as described by the website. There are also faucets that dispense alternative cryptocurrencies.
		The first bitcoin faucet was called The Bitcoin Faucet and was developed by Gavin Andresen in 2010. It originally gave out 5 bitcoins per person.</p>
	</section>
	<section id="faq" class="text-center">
		<h4 class="text-center"><i class="fas fa-book"></i> FAQ</h4>
		<ul>
			<li>VPN/Proxy/Tor are not allowed</li>
			<li>Adblock is not allowed</li>
			<li>You will be banned if make too much fail claim</li>
		</ul>
	</section>
	<div class="ads">
		<?=$settings['footer_ad']?>
	</div>
	<footer>
		<?php
		# PLEASE DO NOT REMOVE THE COPYRIGHT LINK TO SUPPORT US :) MANY THANKS
		?>
		<p class="text-center">&copy 2018 <a href="<?=$settings['url']?>"><?=$settings['name']?></a> | <span id="copyright-footer">Powered by <a href="https://fapcoin.cc/threads/fap-faucet-script-multi-coin-short-link-antibotlinnks.2/">Fap Faucet Script</a></span></p>
	</footer>
	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
	<script src="asset/js/main.js"></script>
	<script src="asset/js/fap-abl.js"></script>
	<script src="asset/js/antibotlinks.js"></script>
	<?php if (isset($wait)){ ?>
		<script src="asset/js/timer.js"></script>
		<script type="text/javascript">
			$("#CountDownTimer").TimeCircles({ time: { Days: { show: false }, Hours: { show: false } }});
			$("#CountDownTimer").TimeCircles({count_past_zero: false});
			$("#CountDownTimer").TimeCircles({fg_width: 0.05});
			$("#CountDownTimer").TimeCircles({bg_width: 0.5});
			$("#CountDownTimer").TimeCircles();
			$("#CountDownTimer").TimeCircles().addListener(function(unit, amount, total){
				if(total == 0) {
					setTimeout(function() { location.href = '<?=$settings['url']?>'; }, 2000);
				}
			});
		</script>
	<?php } else { echo $antibot_js; } ?>
</body>
</html>