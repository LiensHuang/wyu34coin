<?php
namespace Home\Model;
use Think\Model\ViewModel;
class CommentViewModel extends ViewModel {
   public $viewFields = array(
    'Record'=>array('id','coinDate','week','opid','guiltFreePlay','rest','mandatoryWork','qualityWork','procrastination','coinDayDes'),
     'Discuss'=>array('discuss','_on'=>'Record.id=Discuss.coinDaydesId'),  
     'User'=>array('nickname'=>'commentators', '_on'=>'User.opid=Discuss.commentatorOpid')
    );
 }

 ?>