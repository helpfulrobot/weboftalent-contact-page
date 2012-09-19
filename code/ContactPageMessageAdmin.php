<?php 
class ContactPageMessageAdmin extends ModelAdmin {
  public static $managed_models = array('ContactPageMessage'); // Can manage multiple models
  static $url_segment = 'contactpagemessages'; // Linked as /admin/products/
  static $menu_title = 'ContactPageMessages';
}

 ?>