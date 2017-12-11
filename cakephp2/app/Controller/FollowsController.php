<?php
App::uses('AppController', 'Controller');

class FollowsController extends AppController {

  //読み込むコンポーネントの指定
  public $components = array('Session', 'Auth');

  //どのアクションが呼ばれてもはじめに実行される関数
  public function beforeFilter()
  {
    parent::beforeFilter();
    
    //未ログインでアクセスできるアクションを指定
    //これ以外のアクションへのアクセスはloginにリダイレクトされる規約になっている
    //ほかの　モデルからだとうまく制限ができないかもしれない(調べ切れてない)->ログインしていないならログイン画面にリダイレクト
    //$this->Auth->allow('../users/register', '../users/login');
    $this->Auth->deny("follow","followed","search","setsearchfollow","setfollow","deletefollowed","deletefollow");
  }
  public function follow(){

    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/

    $this->loadModel("User");
    $this->loadModel("Twit");

    //idとユーザ名取得とset
    if($this->Session->check("id")){
      $id=$this->Session->read("id");
      $this->set("id",$id);

      if($this->request->query("viewid")){
          $id=$this->request->query("viewid");
          $this->set("id",$id);
      }

      $this->set("user",$this->User->find("first",array("conditions"=>array("id"=>$id)))["User"]["username"]);
    }

    //getに変な値を打ち込まれた時に数値を範囲内に収める,idの値がを入れるより後に設定
    $page=0;
    if($this->request->query("page")){
      if(intval($this->request->query("page"))<0){
        $page = 0;
      }
      else if(intval($this->request->query("page"))>(($this->Follow->find("count",array("conditions"=>array("user_id"=>$id))))-1)/10){
        $page = intval((($this->Follow->find("count",array("conditions"=>array("user_id"=>$id))))-1)/10);
      }
      else{
        $page = intval($this->request->query("page"));
      }
    }
    $this->set("page",$page);

    //dbの値をビューに投げる,順番はdescで新しいものが上に来るようにする
    //recursiveの値で2階層分以上見られる
    $this->User->recursive = 2;
    //twitall、ユーザの全情報なげる
    $this->set("twitall",$this->User->find("all"));
    //followall,フォローテーブル情報投げる
    $this->set("followall",$this->Follow->find("all",array("conditions"=>$id)));
    //followid、followしている相手のid投げる
    $followarray = array();
    foreach($this->Follow->find("all",array("conditions" => array("user_id"=>$id))) as $followall){
      array_push($followarray,$followall["Follow"]["follow"]);
    }
    $this->set("followid",$followarray);
    //フォロー数、被フォロー数を渡す
    $this->set("follownum",$this->Follow->find("count",array("conditions"=>array("user_id"=>$id))));
    $this->set("followednum",$this->Follow->find("count",array("conditions"=>array("follow"=>$id))));
    //投稿数をビューに投げる
    $this->set("twitnum",$this->Twit->find("count",array("conditions"=>array("user_id"=>$id))));
    //$this->log($this->User->find("all"),LOG_DEBUG);
  }

  public function followed(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/

    $this->loadModel("User");
    $this->loadModel("Twit");

    //idとユーザ名取得とset
    if($this->Session->check("id")){
      $id=$this->Session->read("id");
      $this->set("id",$id);

      if($this->request->query("viewid")){
          $id=$this->request->query("viewid");
          $this->set("id",$id);
      }

      $this->set("user",$this->User->find("first",array("conditions"=>array("id"=>$id)))["User"]["username"]);
    }

    //getに変な値を打ち込まれた時に数値を範囲内に収める,idの値がを入れるより後に設定
    $page=0;
    if($this->request->query("page")){
      if(intval($this->request->query("page"))<0){
        $page = 0;
      }
      else if(intval($this->request->query("page"))>(($this->Follow->find("count",array("conditions"=>array("follow"=>$id))))-1)/10){
        $page = intval((($this->Follow->find("count",array("conditions"=>array("follow"=>$id))))-1)/10);
      }
      else{
        $page = intval($this->request->query("page"));
      }
    }
    $this->set("page",$page);

    //dbの値をビューに投げる,順番はdescで新しいものが上に来るようにする
    //recursiveの値で2階層分以上見られる
    $this->User->recursive= 2;
    //twitall、ユーザの全情報なげる
    $this->set("twitall",$this->User->find("all"));
    //followid、followしている相手のid投げる
    $followedarray = array();
    foreach($this->Follow->find("all",array("conditions" => array("follow"=>$id))) as $followedall){
      array_push($followedarray,$followedall["Follow"]["user_id"]);
    }
    $this->set("followedid",$followedarray);
    //followall,フォローテーブル情報投げる
    $this->set("followall",$this->Follow->find("all",array("conditions"=>$id)));
    //フォロー数、被フォロー数を渡す
    $this->set("followednum",$this->Follow->find("count",array("conditions"=>array("follow"=>$id))));
    $this->set("follownum",$this->Follow->find("count",array("conditions"=>array("user_id"=>$id))));
    //投稿数をビューに投げる
    $this->set("twitnum",$this->Twit->find("count",array("conditions"=>array("user_id"=>$id))));
    //$this->log($this->User->find("all"),LOG_DEBUG);
  }

  public function deletefollow(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/
    $this->Follow->delete($this->request->query("deleteid"));
    return $this->redirect("/follows/follow");
  }
  public function deletefollowed(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/
    $this->Follow->delete($this->request->query("deleteid"));
    return $this->redirect("/follows/followed");
  }
  public function setfollow(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/
    $this->Follow->save($this->request->data);
    return $this->redirect("/follows/followed");
  }
  public function setsearchfollow(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/
    $this->Follow->save($this->request->data);
    return $this->redirect("/follows/search?word=".$this->request->query("getkey"));
  }

  public function search(){
    /*if(!$this->Session->check("id")){
      return $this->redirect("/users/login");
    }*/

    //他のモデル(テーブル)読み込み
    $this->loadModel("User");
    $this->loadModel("Twit");

    //id取得とset
    if($this->Session->check("id")){
      $id=$this->Session->read("id");
      $this->set("id",$id);
    }

    //ユーザ名取得
    if($this->Session->check("user")){
      $username=$this->Session->read("user");
      $this->set("username",$username);
    }

    //フォローしているid取得
    $followarray = array();
    foreach($this->Follow->find("all",array("conditions" => array("user_id"=>$id))) as $followall){
      array_push($followarray,$followall["Follow"]["follow"]);
    }
    $this->set("followid",$followarray);

    $word="";
    //クエリの値から部分一致で当て嵌まるユーザ情報をビューに渡す
    $searchuser = array();
    if($this->request->query("word")){
      $word = "%".$this->request->query("word")."%";
      $searchuser = $this->User->find("all",array("conditions"=>array("OR"=>array("username LIKE" => $word,"name LIKE" => $word))));
    }
    $this->set("searchuser",$searchuser);

    //getに変な値を打ち込まれた時に数値を範囲内に収める
    $page=0;
    if($this->request->query("page")){
      if(intval($this->request->query("page"))<0){
        $page = 0;
      }
      else if(intval($this->request->query("page"))>(($this->User->find("count",array("conditions"=>array("OR"=>array("username LIKE" => $word,"name LIKE" => $word)))))-1)/10){
        $page = intval((($this->User->find("count",array("conditions"=>array("OR"=>array("username LIKE" => $word,"name LIKE" => $word)))))-1)/10);
      }
      else{
        $page = intval($this->request->query("page"));
      }
    }
    $this->set("page",$page);

    //フォロー数、被フォロー数を渡す
    $this->set("followednum",$this->Follow->find("count",array("conditions"=>array("follow"=>$id))));
    $this->set("follownum",$this->Follow->find("count",array("conditions"=>array("user_id"=>$id))));
    //投稿数をビューに投げる
    $this->set("twitnum",$this->Twit->find("count",array("conditions"=>array("user_id"=>$id))));
  }
}
