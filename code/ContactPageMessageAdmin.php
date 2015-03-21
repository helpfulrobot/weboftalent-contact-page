<?php
class ContactPageMessageAdmin extends ModelAdmin {
	// Can manage multiple models
	public static $managed_models = array('ContactPageMessage');

	// Linked as /admin/products/
	static $url_segment = 'contactpagemessages';

	static $menu_title = 'ContactPageMessages';
}
