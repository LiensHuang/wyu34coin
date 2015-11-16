<?php
namespace Home\Model;
use Think\Model\ViewModel;

class InfoViewModel extends ViewModel{
	public $viewFields = array(  
	'Information'=>array('id','opid','date','coinNum','timeSlot','coinDes','coinType','coinLocation'),
	    'Record'=>array('week','_on'=>'Information.date=Record.coinDate and Information.opid=Record.opid')
	    
	  );
}
?>