<?php
class ContactPageMessage extends DataObject {

	public static $db = array(
		'Name' => 'Varchar(255)',
		'Email' => 'Varchar(255)',
		'Comments' => 'Text',
		'RepliedTo' => 'Boolean'
	);



 // tidy up the CMS by not showing these fields
	public function getCMSFields() {
 	$fields = parent::getCMSFields();
		$fields->renameField('Name', _t('ContactPageMessage.NAME', 'Name'));
		$fields->renameField('Email', _t('ContactPageMessage.EMAIL', 'Email'));
		$fields->renameField('Comments', _t('ContactPageMessage.COMMENTS', 'Comments'));
		$fields->renameField('RepliedTo', _t('ContactPageMessage.REPLIED_TO', 'Replied To'));
	return $fields;
	}

}
?>
