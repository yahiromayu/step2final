<div class="left">
<h1>ツイッターに参加しました。</h1>
<p><?php
	if($this->Session->check("username")){
		echo $this->Session->read("username");
	}else{
		echo "？？？";
	}
?>さんはついったーに参加されました。</p>
<p>ログインをクリックしてログインしてつぶやいてください。</p>
<a href="/step2final/cakephp2/users/login"><button>twitterにログイン</button></a>
</div>