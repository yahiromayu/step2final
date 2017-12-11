<?php
	echo $this->Html->script("loginCheck");
?>
<div class="left">
<h1>ログイン</h1>
<?php
print($this->Session->flash());
print(
	$this->Form->create("User") .
	$this->Form->input("username", array('type'=>'text','label'=>"ユーザID")) .
	$this->Form->input("password", array('type'=>'password','label'=>"パスワード")) .
	$this->Form->end("ログイン")
);
?>

</div>
<div class="right">
<p>ユーザ登録(無料)</p>
<a href="/step2final/cakephp2/users/register"><button>ユーザ登録</button></a>
</div>
