<?php
	echo $this->Html->script("twitCheck");
?>
<div class="left">
<h1>いまなにしてる？</h1>
<?php
print($this->Session->flash());
print(
	$this->Form->create("Twit",array("type"=>"post")) .
	$this->Form->input("twit", array('type'=>'textarea','label'=>"")) .
	$this->Form->end("投稿する",array("id"=>"twit"))
);//idの値がブラウザ側で見えてしまっている(念のため消したい)
?>
<div id="latestTwit">最近のつぶやき：<br>
<?php
foreach($twitall as $twit):
	if($twit["User"]["id"]==$id){
		print(nl2br("<div class='comment'>".h($twit["Twit"]["twit"])."
		(".$twit["Twit"]["time"].")</div>"));
		break;
	}
endforeach;

?>
</div>

<h2>ホーム</h2>

<?php
	print(($page+1)."ページ目<br><br>");
	$viewcount=0;
	foreach($twitall as $twit):
	$viewcount = $viewcount+1;
	if($viewcount>$page*10 && $viewcount<=($page+1)*10){
		print(nl2br("<div class='comment'><a href='/step2final/cakephp2/twits/twitview?viewid=".$twit["Twit"]["user_id"]."'><font color='blue'>".$twit["User"]["username"]."</font></a> ".h($twit["Twit"]["twit"])." 
		".$twit["Twit"]["time"]."</div>"));

		if($twit["Twit"]["user_id"]!=$id){
			print("<br><hr>");
		}
		else{
			print(nl2br($this->Html->link("<button>削除</button>","/twits/delete?deleteid=".$twit["Twit"]["id"],array("escape"=>false),"Sure you want to delete this tweet? There is NO undo!")."
			<hr>"));
		}
	}
	endforeach;

	print("<a href='/step2final/cakephp2/twits/twit?page=".($page-1)."'><button>前の10件</button></a>");
	print(($page+1)."ページ目");
	print("<a href='/step2final/cakephp2/twits/twit?page=".($page+1)."'><button>次の10件</button></a>");
?>



</div>
<div class="right">
<h3><?php print($username); ?></h3>
<a href="/step2final/cakephp2/follows/follow"><div id="follow">フォローしている <?php print($follownum); ?></div></a>
<a href="/step2final/cakephp2/follows/followed"><div id="followed">フォローされている <?php print($followednum); ?></div></a>
<a href="/step2final/cakephp2/twits/twitview"><div id="postnum">投稿数 <?php print($twitnum); ?></div></a>
</div>
