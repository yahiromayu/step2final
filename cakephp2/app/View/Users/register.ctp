<?php
	echo $this->Html->script("registerCheck");
?>
<div class="left">
<h1>ついったーに参加しましょう</h1>
<div id="hadRegistered">もうついったーに登録していますか？<a href="/step2final/cakephp2/users/login">ログイン</a></div>
<?php
print(
	$this->Form->create("User") .
	$this->Form->input("name", array('type'=>'text','label'=>"名前")) .
	$this->Form->input("username", array('type'=>'text','label'=>"ユーザID")) .
	$this->Form->input("password", array('type'=>'password','label'=>"パスワード")) .
	$this->Form->input("passwordCheck", array('type'=>'password','label'=>"パスワード(確認)")) .
	$this->Form->input("email", array('type'=>'text','label'=>"Eメール")) .
	$this->Form->input("private", array('type'=>'checkbox','label'=>"つぶやきを非公開にする")) .
	$this->Form->end("アカウント作成")
);
?>
</div>
