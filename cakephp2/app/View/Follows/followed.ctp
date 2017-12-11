<div class="left">
<h1><?php if($user!=null)print($user); else print("error") ?>：<?php print($followednum); ?>人にフォローされています。</h1>
<?php
	$viewcount=0;
	print(($page+1)."ページ目<br><br>");
	//twitallで各ユーザの情報を取り、最新の呟きを表示
	//171129時点、まだフォロー被フォロー関係ができていないので全員表示
	foreach($twitall as $twit):
		if(in_array($twit["User"]["id"],$followedid)){
			$viewcount=$viewcount+1;
			if($viewcount>$page*10 && $viewcount<=($page+1)*10){
				$swt=false;
				print(nl2br("<div class='comment'><a href='/step2final/cakephp2/twits/twitview?viewid=".$twit["User"]["id"]."'><font color='blue'>".$twit["User"]["username"]."</font></a> / ".$twit["User"]["name"]." 
				".h(end($twit["Twit"])["twit"])." 
				".end($twit["Twit"])["time"]."</div>"
				));//コメントは最後(最新)の要素を表示

				foreach($followall as $followed){
					if($followed["Follow"]["user_id"] == $id && $followed["Follow"]["follow"] == $twit["User"]["id"]){
						$followid = $followed["Follow"]["follow"];
						$swt = true;
						break;
					}
				}
				if($swt==false && $id==$this->Session->read("id")){
				print(
					$this->Form->create("Follow",array("type"=>"post","url"=>"/follows/setfollow","onsubmit"=>"return confirm('このユーザをフォローしますか？');")) .
					$this->Form->input("user_id", array('type'=>'hidden',"value"=>$id)) .
					$this->Form->input("follow", array('type'=>'hidden',"value"=>$twit["User"]["id"])) .
					$this->Form->end("フォローする")."
					<hr>
					"
				);
				}
				else{
					print("<br><hr>");
				}
			}
		}
	endforeach;

	print("<a href='/step2final/cakephp2/follows/followed?viewid=".$id."&page=".($page-1)."'><button>前の10件</button></a>");
	print(" ".($page+1)."ページ目 ");
	print("<a href='/step2final/cakephp2/follows/followed?viewid=".$id."&page=".($page+1)."'><button>次の10件</button></a>");
?>
</div>
<div class="right">
<h3>名前：<?php print($user); ?></h3>
	<a href="/step2final/cakephp2/follows/follow?viewid=<?php print($id);?>"><div><?php print($follownum);?>人フォローしている</div></a>
	<a href="/step2final/cakephp2/follows/followed?viewid=<?php print($id);?>"><div><?php print($followednum);?>人フォローされている</div></a>
	<a href="/step2final/cakephp2/twits/twitview?viewid=<?php print($id);?>"><div>つぶやき <?php print($twitnum); ?></div></a>
</div>
