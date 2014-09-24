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
class ContactPage extends Page
{
	static $db = array(
		
		'ContactAddress' => 'Text',
		'ContactTelephoneNumber' => 'Varchar(255)',
		'ContactFaxNumber' => 'Varchar(255)',
		'ContactEmailAddress' => 'Varchar(255)',
		'Mailto' => 'Varchar(100)', //Email address to send submissions to
		'SubmitText' => 'HTMLText', //Text presented after submitting message,
		'Twitter' => 'Varchar(255)',
		'Facebook' => 'Varchar(255)'
	);

	private static $icon = 'contactage/icons/phone.png'; 




	public function Map() {
	    $map = $this->owner->RenderMap();
	    // $map->setDelayLoadMapFunction( true );
	    $map->setZoom( 10 );
	    $map->setAdditionalCSSClasses( 'fullWidthMap' );
	    $map->setShowInlineMapDivStyle( true );
	    $map->setClusterer(false);
	    //$map->addKML('http://assets.tripodtravel.co.nz/cycling/meuang-nont-to-bang-sue-loop.kml');
	    return $map;
	  }



	//CMS fields
	function getCMSFields() {
		$fields = parent::getCMSFields();

		//            _t('Header.ADDRESS','Address that will appear in the header of each page')
		$addresstabname = 'Root.'._t('ContactPage.ADDRESS', 'Address');
		$socialmediatabname = 'Root.'._t('ContactPage.SOCIAL_MEDIA', 'Social Media');


		$fields->addFieldToTab( "Root.OnSubmission",
			new TextField( 'Mailto', _t( 'ContactPage.EMAIL_SUBMISSIONS_TO', 'Email submissions to' )
			) );

		$fields->addFieldToTab( "Root.OnSubmission",
			new HTMLEditorField( 'SubmitText', _t( 'ContactPage.TEXT_SHOWN_AFTER_SUBMISSION', 'Text on Submission' ) ) );

		$fields->addFieldToTab( $addresstabname, new TextAreaField( 'ContactAddress', _t( 'ContactPage.ADDRESS', 'Address' ) ) );
		$fields->addFieldToTab( $addresstabname, new TextField( 'ContactTelephoneNumber',
				_t( 'ContactPage.CONTACT_TELEPHONE_NUMBER', 'Contact Tel. Number' ) ) );
		$fields->addFieldToTab( $addresstabname, new TextField( 'ContactFaxNumber',
				_t( 'ContactPage.CONTACT_FAX_NUMBER', 'Contact Fax Number' ) ) );
		$fields->addFieldToTab( $addresstabname, new TextField( 'ContactEmailAddress',
				_t( 'ContactPage.CONTACT_EMAIL_ADDRESS_ADMIN', '(TH) Contact Email Address' ) ) );

		$fields->addFieldToTab( $socialmediatabname, new TextField( 'Facebook',
				_t( 'ContactPage.FACEBOOK_URL', 'Facebook URL' ) ) );
		$fields->addFieldToTab( $socialmediatabname, new TextField( 'Twitter',
				_t( 'ContactPage.TWITTER_USERNAME', 'Twitter Username' ) ) );

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



	function init() {
		//add a javascript library for easy interaction with the server
		Requirements::javascript( 'mysite/javascript/jQuery.js' );
		if ( Director::is_ajax() ) {
			$this->isAjax = true;
		}
		else {
			$this->isAjax = false;
		}
		parent::init();
	}




	function index() {
		error_log( "Contact page index" );
		error_log( "AJAX? ".$this->isAjax );

		if ( $this->isAjax ) {
			return $this->renderWith( "ContactPageModal" );
		}
		else {
			return array();
		}

	}

	//The function which generates our form
	function ContactForm() {
		error_log( "Render form" );
		$name = _t( 'ContactPage.NAME', 'Name' );
		$email = _t( 'ContactPage.EMAIL', 'Email' );
		$comments = _t( 'ContactPage.COMMENTS', 'Comments' );
		$send = _t( 'ContactPage.SEND', 'Send' );

		// Create fields
		$tf = new TextField( 'Name', $name );
		$tf->addExtraClass( 'span11' );


		$ef = new EmailField( 'Email', $email );
		$ef->addExtraClass( 'span11' );

		$taf = new TextareaField( 'Comments', $comments );
		$taf->addExtraClass( 'span11' );



		$fields = new FieldList(
			$tf,
			$ef,
			$taf
		);

		// Create action
		$fa = new FormAction( 'SendContactForm', $send );

		// for bootstrap
		$fa->useButtonTag = true;
		$fa->addExtraClass( 'btn btn-primary buttonright' );

		$actions = new FieldList(
			$fa
		);

		// Create action
		$validator = new RequiredFields( 'Name', 'Email', 'Comments' );



		$form = new Form( $this, 'ContactForm', $fields, $actions, $validator );
		$form->setTemplate( 'VerticalForm' );
		$form->addExtraClass( 'well' );

		if(class_exists('SpamProtectorManager')) {
        	$form->enableSpamProtection();
    	}

		return $form;
	}

	//The function that handles our form submission
	function SendContactForm( $data, $form ) {

		// saving data before sending contact form
		$cpm = new ContactPageMessage();
		$cpm->Email = $data['Email'];
		$cpm->Name = $data['Name'];
		$cpm->Comments = $data['Comments'];
		$cpm->write();

	
		error_log( "Sending contact form" );

		//Set data
		$From = $data['Email'];
		//$From = Email::getAdminEmail();

		$To = $this->Mailto;
		$Subject = $this->SiteConfig()->Title.' - ';
		$Subject .= "Website Contact message";
		$email = new Email( $From, $To, $Subject );
		//set template
		$email->setTemplate( 'ContactEmail' );
		//populate template
		$email->populateTemplate( $data );
		//send mail
		$email->send();
		//return to submitted message

		if ( $this->isAjax ) {
			$result = array();


			$result['message'] = $this->SubmitText;
			$result['success'] = 1;

			echo json_encode( $result );
			die;
		}
		else {
			Controller::redirect( Director::baseURL(). $this->URLSegment . "/?success=1" );
		}
		//

	}

	//The function to test whether to display the Submit Text or not
	public function Success() {
		return isset( $_REQUEST['success'] ) && $_REQUEST['success'] == "1";
	}


	public function HasGeo() {
		return (($this->Latitude !=0) && ($this->Longitude != 0));
	}


	public function HasSocialMedia() {
		return $this->Twitter || $this->Facebook;
	}

	public function HasTelecomAddress() {
		return $this->ContactEmailAddress || $this->ContactFaxNumber || $this->ContactTelephoneNumber;
	}


	public function ColumnLayout() {
		return 'layout2col';
	}


}
