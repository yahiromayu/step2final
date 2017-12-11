var error = "";

$(function(){
	$("form").submit(function(){
		if($("textarea[name='data[Twit][twit]']").val()===""){
			error = error + "つぶやきを入力してください。\n";
		}
		//空白のみでの投稿を拒否する、クリティカルな問題ではないのでサーバでのチェックはいれていない。
		if($("textarea[name='data[Twit][twit]']").val().match(/^[ 　\r\n\t]*$/)){
			error = error + "空白のみでは投稿できません。\n";
		}
		if(error != ""){
			alert(error);
			error = "";
			return false;
		}
	});
});
