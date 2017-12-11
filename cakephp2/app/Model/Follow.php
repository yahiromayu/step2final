<?php
App::uses('AppModel', 'Model');
 
class Follow extends AppModel {
	public $belongsTo = "User";
}