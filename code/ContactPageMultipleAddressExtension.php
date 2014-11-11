<?php 
class ContactPageMultipleAddressExtension extends Extension {
	public function updateContactPageForm(&$fields) {
		$fields->removeByName('ContactAddress');
		$fields->removeByName('ContactTelephoneNumber');
		$fields->removeByName('ContactFaxNumber');
		$fields->removeByName('Location');
	}

	/*
	Helper method for a template 
	*/
	public function HideAddressAndPhoneDetails() {
		return true;
	}
}
