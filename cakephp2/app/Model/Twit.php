<?php
App::uses('AppModel', 'Model');
 
class Twit extends AppModel {
	public $belongsTo = "User";

  //入力チェック機能
  public $validate = array(
    'twit' => array(
      array(
        'rule' => array('minLength', 1),
        'message' => '文字を入力してください。'
      ),
      array(
        'rule' => array('maxLength', 140),
        'message' => ""//赤線が入ってしまう、対策がいまいち思いつかない
      )
    )
  );
}