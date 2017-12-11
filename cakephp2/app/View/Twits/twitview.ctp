<div class="left">
<h1><?php print($username); ?>のついーと</h1>

<?php
	print(($page+1)."ページ目<br><br>");
	$viewcount=0;
	foreach($twitall as $twit):
	$viewcount=$viewcount+1;
	if($viewcount>$page*10 && $viewcount<=($page+1)*10){
		print(nl2br("<div class='comment'><a href='/cakephp2/twits/twitview?viewid=".$twit["Twit"]["user_id"]."'><font color='blue'>".$twit["User"]["username"]."</font></a> ".h($twit["Twit"]["twit"])." 
		".$twit["Twit"]["time"]."</div>"));

		if($this->request->query("viewid")&&$this->request->query("viewid")!=$this->Session->read("id")){
			print("<br><hr>");
		}else{
			print(nl2br($this->Html->link("<button>削除</button>","/twits/delete?deleteid=".$twit["Twit"]["id"],array("escape"=>false),"Sure you want to delete this tweet? There is NO undo!")."
			<hr>"));
		}
	}
	endforeach;

	print("<a href='/step2final/cakephp2/twits/twitview?viewid=".$id."&page=".($page-1)."'><button>前の10件</button></a>");
	print(" ".($page+1)."ページ目 ");
	print("<a href='/step2final/cakephp2/twits/twitview?viewid=".$id."&page=".($page+1)."'><button>次の10件</button></a>");
?>


</div>
<div class="right">
<h3><?php print($username); ?></h3>
<a href="/step2final/cakephp2/follows/follow?viewid=<?php print($id); ?>"><div id="follow">フォローしている <?php print($follownum); ?></div></a>
<a href="/step2final/cakephp2/follows/followed?viewid=<?php print($id); ?>"><div id="followed">フォローされている <?php print($followednum); ?></div></a>
<a href="#"><div id="postnum">投稿数 <?php print($twitnum); ?></div></a>
</div>
