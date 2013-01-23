<?php
class ContactPageMessage extends DataObject {

	public static $db = array(
		'Name' => 'Varchar(255)',
		'Email' => 'Varchar(255)',
		'Comments' => 'Text',
		'RepliedTo' => 'Boolean'
	);

}
?>