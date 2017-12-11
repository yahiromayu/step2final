var error = "";

$(function(){
	$("form").submit(function(){
		if($("input[name='data[User][name]']").val()===""){
			error = error + "名前を入力してください。\n";
		}
		if($("input[name='data[User][username]']").val()===""){
			error = error + "ユーザidを入力してください。\n";
		}
		if($("input[name='data[User][password]']").val()===""){
			error = error + "パスワードを入力してください。\n";
		}
		if($("input[name='data[User][passwordCheck]']").val()===""){
			error = error + "パスワード(確認)を入力してください。\n";
		}
		if($("input[name='data[User][email]']").val()===""){
			error = error + "Eメールを入力してください。\n";
		}
		if(error != ""){
			alert(error);
			error = "";
			return false;
		}
	});
});
