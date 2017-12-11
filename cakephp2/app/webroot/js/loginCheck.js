var error = "";

$(function(){
	$("form").submit(function(){
		if($("input[name='data[User][username]']").val()===""){
			error = error + "名前を入力してください。\n";
			alert("a");
		}
		if($("input[name='data[User][password]']").val()===""){
			error = error + "パスワードを入力してください。\n";
			alert("b");
		}
		if(error != ""){
			alert(error);
			error = "";
			return false;
		}
	});
});
