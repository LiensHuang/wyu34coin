<?php
namespace Home\Model;
use Think\Model\RelationModel;

class RecordModel extends RelationModel{
	 protected $_link = array(
        'User' => array(
			    'mapping_type'  => self::BELONGS_TO ,
			    'class_name'    => 'User',
			    'foreign_key'   =>'opid',
	            'mapping_fields'=>'nickname',
	            'as_fields'=>'nickname'
		        ),
	    'Likes' => array(
//			    'mapping_type'  => self::HAS_MANY ,
//			    'class_name'    => 'Likes',
//			    'foreign_key'   =>'id'
//	            'mapping_fields'=>'good',
//	            'as_fields'=>'good1'
		        ) ,
		'Discuss'=>array()
		        			
		);
}
?>