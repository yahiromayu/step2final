<?php
App::uses('AppModel', 'Model');
 
class User extends AppModel {

  public $hasMany = array(
  	"Twit" => array(
  		"className" => "Twit"
	   ),
  	"Follow" => array(
  		"className" => "Follow"
	   )
  );

  //入力チェック機能
  public $validate = array(
    'name' => array(
      array(
        "rule" => array("minLength",1),
        "message" => "※文字を入力してください。"
      ),
      array(
        'rule' => 'nameRestrict', //半角英数字のみ
        'message' => '※名前に-と_以外の記号は使用できません。'
      ),
      array(
        'rule' => array('between', 4, 20), //2～32文字
        'message' => '※名前は4文字以上20文字以内にしてください。'
      )
    ),
    'username' => array(
      array(
        "rule" => array("minLength",1),
        "message" => "※文字を入力してください。"
      ),
      array(
        'rule' => 'isUnique', //重複禁止
        'message' => '※既に使用されている名前です。'
      ),
      array(
        'rule' => 'usernameRestrict', //半角英数字のみ
        'message' => '※半角英数字及び-と_以外の記号は使用できません。'
      ),
      array(
        'rule' => array('between', 4, 20), //2～32文字
        'message' => '※名前は4文字以上20文字以内にしてください。'
      )
    ),
    'password' => array(
      array(
        "rule" => array("minLength",1),
        "message" => "※文字を入力してください。"
      ),
      array(
        'rule' => 'alphaNumeric',
        'message' => '※パスワードは半角英数字にしてください。'
      ),
      array(
        'rule' => array('between', 4, 8),
        'message' => '※パスワードは4文字以上8文字以内にしてください。'
      )
    ),
    'passwordCheck' => array(
      array(
        "rule" => array("minLength",1),
        "message" => "※文字を入力してください。"
      ),
	    'rule' => array('checkPassword', "password"),//passwordを第二引数として渡して一致するか確認
        'message' => '※パスワードが一致しません。'
    ),
    'email' => array(
      array(
        "rule" => array("minLength",1),
        "message" => "※文字を入力してください。"
      ),
  		array(
  			"rule" => array("maxLength",100),
  			"message" => "※アドレスが長すぎます。"
  		),
      	array(
  	    	"rule" => "email",//email形式
      		"message" => "※有効なアドレスを入力してください。"
  		)
  	)
  );
  
  public function beforeSave($options = array()) {
    $this->data['User']['password'] = AuthComponent::password($this->data['User']['password']);
    return true;
  }

  //名前に正規表現を用いて全角文字、カタカナ、ひらがな、-、_追加
  //参考：http://wepicks.net/phpsample-preg-str-2/
  public function usernameRestrict($check){
	$value = array_values($check);//連想配列のデータ部分を取り出す
	$value = $value[0];//array_valuesは配列で値を渡されるので0番目を指定してデータを変数に入れる
	return preg_match("/^[a-zA-Z0-9-_]+$/",$value);
  }

  public function nameRestrict($check){
	$value = array_values($check);//連想配列のデータ部分を取り出す
	$value = $value[0];//array_valuesは配列で値を渡されるので0番目を指定してデータを変数に入れる
	return preg_match("/^[ぁ-ん一-龠Ａ-Ｚａ-ｚァ-ヶa-zA-Z0-9-_]+$/",$value);
  }

  //パスワード確認が同一かどうかチェック
  //参考：http://takuya-1st.hatenablog.jp/entry/20121227/1356632700
  //参考：https://book.cakephp.org/2.0/ja/models/data-validation.html#id5
  public function checkPassword($check,$field_name){
	$value = array_values($check);
	$v1 = $value[0];
  	$v2 = $this->data["User"][$field_name];
	return $v1 == $v2;
  }
}
