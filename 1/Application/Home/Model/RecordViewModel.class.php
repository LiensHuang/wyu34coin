<?php
namespace Home\Model;
use Think\Model\ViewModel;
class RecordViewModel extends ViewModel {
   public $viewFields = array(
    'Record'=>array('id','coinDate','week','opid','guiltFreePlay','rest','mandatoryWork','qualityWork','procrastination','coinDayDes'),
     'Likes'=>array('_on'=>'Record.id=Likes.informationid'),  
     'User'=>array('nickname', '_on'=>'User.opid=Likes.opid')
    );
 }

 ?>