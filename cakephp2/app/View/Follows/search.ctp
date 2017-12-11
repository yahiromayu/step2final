<div class="left">
<h1>友だちを見つけて、フォローしましょう！</h1>
<p>ついったーに登録済みの友達を検索できます。</p>

<?php

print(
	"誰を検索しますか？
	".$this->Form->create(null,array("type"=>"get")) .
	$this->Form->input("word", array('type'=>'text','label'=>"")) .
	$this->Form->end("検索")
);
print(($page+1)."ページ目<br><br>");

if(empty($searchuser)){
	print("対象のユーザは見つかりません");
}

$notfoundflg=true;
$viewcount=0;
foreach($searchuser as $user):
	if($user["User"]["id"]!=$id && !in_array($user["User"]["id"],$followid)){

		$viewcount=$viewcount+1;
		if($viewcount>$page*10 && $viewcount<=($page+1)*10){
			print(nl2br("<div class='comment'><a href='/step2final/cakephp2/twits/twitview?viewid=".$user["User"]["id"]."'><font color='blue'>".$user["User"]["username"]."</font></a> / ".$user["User"]["name"]." 
			".h(end($user["Twit"])["twit"])." 
			".end($user["Twit"])["time"]."</div>"));

			print(
				$this->Form->create("Follow",array("type"=>"post","url"=>"/follows/setsearchfollow?getkey=".$this->request->query("word"),"onsubmit"=>"return confirm('このユーザをフォローしますか？');")) .
				$this->Form->input("user_id", array('type'=>'hidden',"value"=>$id)) .
				$this->Form->input("follow", array('type'=>'hidden',"value"=>$user["User"]["id"])) .
				$this->Form->end("フォローする")
				."<hr>"
			);
			$notfoundflg=false;
		}
	}
endforeach;

//Userモデルから表示しているのでフォローしているユーザを省くと何も読み込めないページが出てきてしまうため、再帰的に呼び出して調整
if($notfoundflg && intval($page)==0){
	print("対象のユーザは見つかりません。<br><br>");
}
else if($notfoundflg && intval($page)>0){
	header("Location: /step2final/cakephp2/follows/search?page=".(intval($page)-1)."&word=".$this->request->query("word"));
	exit();
}

print("<a href='/step2final/cakephp2/follows/search?page=".($page-1)."&word=".$this->request->query("word")."'><button>前の10件</button></a>");
print(" ".($page+1)."ページ目 ");
print("<a href='/step2final/cakephp2/follows/search?page=".($page+1)."&word=".$this->request->query("word")."'><button>次の10件</button></a>");

?>
</div>
<div class="right">
<h3>名前：<?php print($username); ?></h3>
	<a href="/step2final/cakephp2/follows/follow"><div><?php print($follownum);?>人フォローしている</div></a>
	<a href="/step2final/cakephp2/follows/followed"><div><?php print($followednum);?>人フォローされている</div></a>
	<a href="/step2final/cakephp2/twits/twitview"><div>つぶやき <?php print($twitnum); ?></div></a>
</div>
