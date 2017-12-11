<?php
App::uses('AppController', 'Controller');

class UsersController extends AppController {

  //読み込むコンポーネントの指定
  public $components = array('Session', 'Auth');
  
  //どのアクションが呼ばれてもはじめに実行される関数
  public function beforeFilter()
  {
    parent::beforeFilter();
    
    //未ログインでアクセスできるアクションを指定
    //これ以外のアクションへのアクセスはloginにリダイレクトされる規約になっている
    $this->Auth->allow('register', 'login');
    //$this->Auth->deny("index","logout");
  }
  
  public function register(){
    if($this->Session->check("id")){
      return $this->redirect("/twits/twit");
    }

    //$this->set("valerr",null);
  	//$this->requestにPOSTされたデータが入っている
  	//POSTメソッドかつユーザ追加が成功したら
    if($this->request->is('post') && $this->User->save($this->request->data)){
      //ログイン
      //$this->request->dataの値を使用してログインする規約になっている
      
      //$this->Auth->login();
      //中身確認tmp/logs/debug.logに表示
      //$this->log($this->request->data("User.username"),LOG_DEBUG);
      //ログイン前にusernameの値を使いたいのでsessionに名前を一旦保存、registerfinished.ctpで取り出す、keyは"username"
      $this->Session->write("username",$this->request->data("User.username"));
      return $this->redirect(["action" => 'registerfinished']);
    }else{
	  	$this->Session->setFlash($this->User->validationErrors);
    }
  }

  //ログイン後遷移ページ
  public function registerfinished(){
    if($this->Session->check("id")){
      return $this->redirect("/twits/twit");
    }

  }

  public function login(){
    if($this->request->is('post')){//デフォルトでpost
    	$this->User->set($this->request->data);
    	unset($this->User->validate["username"][1]);//なんか動かない
    	if($this->User->validates()) {
		  if($this->Auth->login()){
		    //ログインユーザ名取得
		    $user = $this->request->data("User.username");
		    //ログイン中はsessionにidを保存
		    $this->Session->write("id",$this->User->find("first",array("conditions"=>array("username"=>$user)))["User"]["id"]);
		    //ログイン中はsessionにユーザ名保存
		    $this->Session->write("user",$user);
		    return $this->redirect('/twits/twit');
		  }
		  else{
		    $this->Session->setFlash('<div class="error-message">※ユーザ名とパスワードが一致しません。</div>');
		  }
		}
	}

    if($this->Session->check("id")){
      return $this->redirect("/twits/twit");
    }
  }
  
  public function logout(){
    //ログアウト時にsessionの値を消す
    $this->Session->destroy();
    $this->Auth->logout();
    $this->redirect('login');
  }
}
