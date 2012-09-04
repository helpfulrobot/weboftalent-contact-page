<?php
/******************
 * 
 * ContactPage
 * 
 * Tutorial on www.ssbits.com/creating-a-simple-contact-form/
 * 
 * Author: Aram Balakjian of aabweb.co.uk
 * 
 ******************/

//Model
class ContactPage extends Page  implements Mappable
{
	static $db = array(
		   'Lat' => 'Varchar',
        	'Lon' => 'Varchar',

        'ContactAddress' => 'HTMLText',
        'ContactTelephoneNumber' => 'Varchar(255)',
        'ContactEmailAddress' => 'Varchar(255)',
		'Mailto' => 'Varchar(100)', //Email address to send submissions to
		'SubmitText' => 'HTMLText' //Text presented after submitting message
	);



	/* Mappable interface requirements */

    public function getLatitude() {
        return $this->Lat;
    }

    public function getLongitude() {
        return $this->Lon;
    }

    public function getMapContent() {
        return GoogleMapUtil::sanitize($this->renderWith('GoogleMapInfoWindow'));
    }

    public function getMapCategory() {
        return $this->Type;
    }

    public function getMapPin() {
        return false; // use standard google pin
    }

/* end Mappable interface */




	
	
	//CMS fields
	function getCMSFields() 
	{
		$fields = parent::getCMSFields();
	
		$fields->addFieldToTab("Root.Content.OnSubmission", new TextField('Mailto', 'Email submissions to'));	
		$fields->addFieldToTab("Root.Content.OnSubmission", new HTMLEditorField('SubmitText', 'Text on Submission'));

		$fields->addFieldToTab('Root.Content.Map', new TextField('Lat', 'Latitude of office'));
		$fields->addFieldToTab('Root.Content.Map', new TextField('Lon', 'Longitude of office'));


		$fields->addFieldToTab('Root.Content.Address', new HTMLEditorField('ContactAddress', 'Address'));
		$fields->addFieldToTab('Root.Content.Address', new TextField('ContactTelephoneNumber', 'Contact Tel. Number'));
		$fields->addFieldToTab('Root.Content.Address', new TextField('ContactEmailAddress'));



	
		return $fields;	
	}

}

// Controller
class ContactPage_Controller extends Page_Controller
{
	//Define our form function as allowed
	static $allowed_actions = array(
		'ContactForm',
		'SendContactForm'
	);



	function init(){
		  //add a javascript library for easy interaction with the server
		  Requirements::javascript('mysite/javascript/jQuery.js');
		  if(Director::is_ajax()) {
		   $this->isAjax = true;
		  }
		  else {
		   $this->isAjax = false;
		  } 
		  parent::init();
	}




	function index() {
		error_log("Contact page index");
		error_log("AJAX? ".$this->isAjax);

		 if($this->isAjax) {
   			return $this->renderWith("ContactPageModal");
  		}
  		else {
   			return Array();
  		}
		
	}
	
	//The function which generates our form
	function ContactForm() 
	{
		error_log("Render form");
		$name = _t('ContactPage.NAME', 'Name');
		$email = _t('ContactPage.EMAIL', 'Email');
		$comments = _t('ContactPage.COMMENTS', 'Comments');
		$send = _t('ContactPage.SEND', 'Send');

      	// Create fields
      	$tf = new TextField('Name', $name);
      	$tf->addExtraClass('span6');


      	$ef = new EmailField('Email', $email);
		$ef->addExtraClass('span6');

      	$taf = new TextareaField('Comments',$comments);
		$taf->addExtraClass('span6');



	    $fields = new FieldSet(
			$tf,	    	
			$ef,
			$taf
		);
	 	
	    // Create action
	    $fa = new FormAction('SendContactForm', $send);

	    // for bootstrap
	    $fa->useButtonTag = true;
	    $fa->addExtraClass('btn btn-primary');
	  
	    $actions = new FieldSet(
	    	$fa
	    );
		
		// Create action
		$validator = new RequiredFields('Name', 'Email', 'Comments');


			
	    $form = new Form($this, 'ContactForm', $fields, $actions, $validator);
	    $form->setTemplate('CustomizedForm');
	    $form->addExtraClass('well');
	    return $form;
	}
 	
	//The function that handles our form submission
	function SendContactForm($data, $form) 
	{

		error_log("Sending contact form");

	 	//Set data
		//$From = $data['Email'];
		$From = Email::getAdminEmail();

		$To = $this->Mailto;
		$Subject = "Website Contact message";  	  
		$email = new Email($From, $To, $Subject);
		//set template
		$email->setTemplate('ContactEmail');
		//populate template
		$email->populateTemplate($data);
		//send mail
		$email->send();
	  	//return to submitted message

	  	if($this->isAjax) {
	  		error_log("SCF: AJAX");
	  		$result = array();

	  	
	  		$result['message'] = $this->SubmitText;
	  		$result['success'] = 1;

	  		error_log("ENCODING TO JSON");

	  		echo json_encode($result);
	  		die;
		  }
		  else {
		  	error_log("SCF: NON AJAX");
		   Director::redirect(Director::baseURL(). $this->URLSegment . "/?success=1");
		  }
		//

	}

	//The function to test whether to display the Submit Text or not
	public function Success()
	{
		return isset($_REQUEST['success']) && $_REQUEST['success'] == "1";
	}


	  public function ColumnLayout() {
    	return 'layout2col';
    }

}