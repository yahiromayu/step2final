<?php
App::uses('AppController', 'Controller');

class TwitsController extends AppController {

  //読み込むコンポーネントの指定
  public $components = array('Session', 'Auth');
  
  //どのアクションが呼ばれてもはじめに実行される関数
  public function beforeFilter()
  {
    parent::beforeFilter();
    
    //未ログインでアクセスできるアクションを指定
    //これ以外のアクションへのアクセスはloginにリダイレクトされる規約になっている
    //ほかの　モデルからだとうまく制限ができないかもしれない(調べ切れてない)->ログインしていないならログイン画面にリダイレクト
    //$this->Auth->allow('/step2final/cakephp2/users/register', '/step2final/cakephp2/users/login');
    $this->Auth->deny("twit","twitview","delete");
  }
  public function twit(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/

    //ページングはgetの値を読み込んで使用する、原始的な方法なのでちょっとぐちゃぐちゃになるかも
    //paginatorは時間があったら触れてみる

    //getに変な値を打ち込まれた時に数値を範囲内に収める
    $page=0;
    if($this->request->query("page")){
      if(intval($this->request->query("page"))<0){
        $page = 0;
      }
      else if(intval($this->request->query("page"))>(($this->Twit->find("count"))-1)/10){
        $page = intval((($this->Twit->find("count"))-1)/10);
      }
      else{
        $page = intval($this->request->query("page"));
      }
    }

    $this->set("page",$page);

    $this->loadModel("User");
    $this->loadModel("Follow");

    //id取得とset
    if($this->Session->check("id")){
      $id=$this->Session->read("id");
      $this->set("id",$id);

      //ログイン名を投げる
      $this->set("username",$this->User->find("first",array("conditions"=>array("id"=>$id)))["User"]["username"]);
    }

    //dbの値をビューに投げる,順番はdescで新しいものが上に来るようにする
    $this->set("twitall",$this->Twit->find("all",array("order"=>array("time"=>"desc"))));
    //投稿数をビューに投げる
    $this->set("twitnum",$this->Twit->find("count",array("conditions"=>array("user_id"=>$id))));
    //フォロー数をビューに投げる
    $this->set("follownum",$this->Follow->find("count",array("conditions"=>array("user_id"=>$id))));
    //被フォロー数をビューに投げる
    $this->set("followednum",$this->Follow->find("count",array("conditions"=>array("follow"=>$id))));

    //$this->log($this->Twit->find("all"),LOG_DEBUG);
    //POST送信があったら値をdbに保存してリダイレクトでPOSTの値を消す
    if($this->request->is('post')){
      $tweet = $this->request->data["Twit"]["twit"];
      $saveTweet = array();
      $saveTweet["user_id"] = $this->Auth->user("id");
      $saveTweet["twit"] = $tweet;
      if($this->Twit->save($saveTweet)){
        return $this->redirect("twit");
      }else{
        $this->Session->setFlash('<div class="error-message">※入力は140文字以内にしてください。</div>');
      }
    }
  }

  public function twitview(){
    if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }

    $this->loadModel("Follow");
    $this->loadModel("User");

    //id取得とset
    if($this->Session->check("id")){
      $id=$this->Session->read("id");
      $this->set("id",$id);

      //queryにidがあればビューでの$idの値を上書きしてその人のツイート一覧が見られる
      if($this->request->query("viewid")){
        $this->set("id",$this->request->query("viewid"));
        $id=$this->request->query("viewid");
      }

      //ログイン名を投げる
      $this->set("username",$this->User->find("first",array("conditions"=>array("id"=>$id)))["User"]["username"]);
    }


    //getに変な値を打ち込まれた時に数値を範囲内に収める,idの値がを入れるより後に設定
    $page=0;
    if($this->request->query("page")){
      if(intval($this->request->query("page"))<0){
        $page = 0;
      }
      else if(intval($this->request->query("page"))>(($this->Twit->find("count",array("conditions"=>array("user_id"=>$id))))-1)/10){
        $page = intval((($this->Twit->find("count",array("conditions"=>array("user_id"=>$id))))-1)/10);
      }
      else{
        $page = intval($this->request->query("page"));
      }
    }
    $this->set("page",$page);

    //dbの値をビューに投げる,順番はdescで新しいものが上に来るようにする
    $this->set("twitall",$this->Twit->find("all",array("conditions"=>array("user_id"=>$id),"order"=>array("time"=>"desc"))));
    //投稿数をビューに投げる
    $this->set("twitnum",$this->Twit->find("count",array("conditions"=>array("user_id"=>$id))));
    //フォロー数をビューに投げる
    $this->set("follownum",$this->Follow->find("count",array("conditions"=>array("user_id"=>$id))));
    //被フォロー数をビューに投げる
    $this->set("followednum",$this->Follow->find("count",array("conditions"=>array("follow"=>$id))));
    
  }
  public function delete(){
    if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }
    $this->Twit->delete($this->request->query("deleteid"));
    return $this->redirect("/twits/twit");
  }
}
