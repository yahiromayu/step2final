<div class="left">
<h1><?php if($user!=null)print($user); else print("error") ?>：<?php print($follownum); ?>人をフォローしています。</h1>
<?php
	print(($page+1)."ページ目<br><br>");
	$viewcount=0;
	//twitallで各ユーザの情報を取り、最新の呟きを表示
	//171129時点、まだフォロー被フォロー関係ができていないので全員表示
	foreach($twitall as $twit):
		if(in_array($twit["User"]["id"],$followid)){
			$viewcount=$viewcount+1;
			if($viewcount>$page*10 && $viewcount<=($page+1)*10){
				print(nl2br("<div class='comment'><a href='/step2final/cakephp2/twits/twitview?viewid=".$twit["User"]["id"]."'><font color='blue'>".$twit["User"]["username"]."</font></a> / ".$twit["User"]["name"]." 
				".h(end($twit["Twit"])["twit"])." 
				".end($twit["Twit"])["time"]."</div>"
				));//コメントは最後(最新)の要素を表示

				//Userテーブルを渡しているのでFollowテーブルの情報はループで探し出すしかない、2重でforeach使うと処理遅くなるしviewで処理を書きたくないので別案があれば後で書き換えよう

				foreach($followall as $follow){
					if($follow["Follow"]["user_id"] == $id && $follow["Follow"]["follow"] == $twit["User"]["id"]){
						$deletefollowid = $follow["Follow"]["id"];
						break;
					}
				}
				if($id==$this->Session->read("id")){
				print(nl2br($this->Html->link("<button>フォロー解除</button>","/follows/deletefollow?deleteid=".$deletefollowid,array("escape"=>false),"Sure you want to unfollow?")."
				<hr>"));
				}
				else{
					print("<br><hr>");
				}
			}
		}
	endforeach;

	print("<a href='/step2final/cakephp2/follows/follow?viewid=".$id."&page=".($page-1)."'><button>前の10件</button></a>");
	print(" ".($page+1)."ページ目 ");
	print("<a href='/step2final/cakephp2/follows/follow?viewid=".$id."&page=".($page+1)."'><button>次の10件</button></a>");
?>
</div>
<div class="right">
<h3>名前：<?php print($user); ?></h3>
	<a href="/step2final/cakephp2/follows/follow?viewid=<?php print($id); ?>"><div><?php print($follownum);?>人フォローしている</div></a>
	<a href="/step2final/cakephp2/follows/followed?viewid=<?php print($id); ?>"><div><?php print($followednum);?>人フォローされている</div></a>
	<a href="/step2final/cakephp2/twits/twitview?viewid=<?php print($id);?>"><div>つぶやき <?php print($twitnum); ?></div></a>
</div>
