<!DOCTYPE html>
<html>
	<head>
		<?php echo $this->Html->charset(); ?>
		<title> <?php echo $title_for_layout; ?> / ついったー</title>
		<?php echo $this->Html->css("main"); ?>
		<script src="http://code.jquery.com/jquery-2.0.0.min.js"></script>
	</head>
	<body>
	<div id="container">
		<div id="header">
		<?php echo "<a href='/step2final/cakephp2/twits/twit'>".$this->Html->image("logo.png", array('alt' => 'twitter_logo',"width" => "200px","a" => ""))."</a>"; ?>
			<div id="header_menu">
				<?php
					if(isset($user)):
						echo $this->Html->link(" ホーム ","/users/login");
						echo $this->Html->link(" 友だちを検索 ","/follows/search");
						echo $this->Html->link(" ログアウト ", "/users/logout");
					else:
						echo $this->Html->link(" ホーム ","/users/login");
						echo $this->Html->link(" ログイン ","/users/login");
						echo $this->Html->link(" 新規登録 ","/users/register");
					endif;
				?>
			</div>
			<div id="content">
				<?php echo $this->fetch("content"); ?>
			</div>
		</div>
	</body>
<html>
