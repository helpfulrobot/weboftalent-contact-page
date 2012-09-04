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
		'Latitude' => 'Decimal(9,6)',
		'Longitude' => 'Decimal(9,6)',
		'Zoom' => 'Int',

		'ContactAddress' => 'Text',
		'ContactTelephoneNumber' => 'Varchar(255)',
		'ContactFaxNumber' => 'Varchar(255)',
		'ContactEmailAddress' => 'Varchar(255)',
		'Mailto' => 'Varchar(100)', //Email address to send submissions to
		'SubmitText' => 'HTMLText' //Text presented after submitting message
	);



	/* Mappable interface requirements */

	public function getMappableLatitude() {
		return $this->Latitude;
	}

	public function getMappableLongitude() {
		return $this->Longitude;
	}

	public function getMapContent() {
		return GoogleMapUtil::sanitize( $this->renderWith( 'ContactPageGoogleMapInfoWindow' ) );
	}

	public function getMapCategory() {
		return $this->Type;
	}

	public function getMapPin() {
		return false; // use standard google pin
	}

	/* end Mappable interface */


	// map with styles
	public function Map() {
		$model = DataObject::get_by_id( 'ContactPage', $this->ID );
		//    $prod->SetZoom(4);

		$map = $model->GoogleMap();
		$map->setDelayLoadMapFunction( true );
		$map->setZoom( $model->Zoom );
		$map->setAdditionalCSSClasses( 'fullWidthMap' );
		$map->setShowInlineMapDivStyle( false );

		return $map;
	}






	//CMS fields
	function getCMSFields() {
		$fields = parent::getCMSFields();

		//            _t('Header.ADDRESS','Address that will appear in the header of each page')


		$fields->addFieldToTab( "Root.Content.OnSubmission",
			new TextField( 'Mailto', _t( 'ContactPage.EMAIL_SUBMISSIONS_TO', 'Email submissions to' )
			) );

		$fields->addFieldToTab( "Root.Content.OnSubmission",
			new HTMLEditorField( 'SubmitText', _t( 'ContactPage.TEXT_SHOWN_AFTER_SUBMISSION', 'Text on Submission' ) ) );

		$fields->addFieldToTab( "Root.Content.Location", new LatLongField( array(
					new TextField( 'Latitude', 'Latitude' ),
					new TextField( 'Longitude', 'Longitude' ),
					new TextField( 'Zoom', 'Zoom' )
				),
				array( 'Address' )
			) );

		$fields->addFieldToTab( 'Root.Content.Address', new TextAreaField( 'ContactAddress', _t( 'ContactPage.ADDRESS', 'Address' ) ) );
		$fields->addFieldToTab( 'Root.Content.Address', new TextField( 'ContactTelephoneNumber',
				_t( 'ContactPage.CONTACT_TELEPHONE_NUMBER', 'Contact Tel. Number' ) ) );
		$fields->addFieldToTab( 'Root.Content.Address', new TextField( 'ContactFaxNumber',
				_t( 'ContactPage.CONTACT_FAX_NUMBER', 'Contact Fax Number' ) ) );
		$fields->addFieldToTab( 'Root.Content.Address', new TextField( 'ContactEmailAddress',
				_t( 'ContactPage.CONTACT_EMAIL_ADDRESS_ADMIN', '(TH) Contact Email Address' ) ) );




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



		$fields = new FieldSet(
			$tf,
			$ef,
			$taf
		);

		// Create action
		$fa = new FormAction( 'SendContactForm', $send );

		// for bootstrap
		$fa->useButtonTag = true;
		$fa->addExtraClass( 'btn btn-primary' );

		$actions = new FieldSet(
			$fa
		);

		// Create action
		$validator = new RequiredFields( 'Name', 'Email', 'Comments' );



		$form = new Form( $this, 'ContactForm', $fields, $actions, $validator );
		$form->setTemplate( 'VerticalForm' );
		$form->addExtraClass( 'well' );
		return $form;
	}

	//The function that handles our form submission
	function SendContactForm( $data, $form ) {

		error_log( "Sending contact form" );

		//Set data
		//$From = $data['Email'];
		$From = Email::getAdminEmail();

		$To = $this->Mailto;
		$Subject = "Website Contact message";
		$email = new Email( $From, $To, $Subject );
		//set template
		$email->setTemplate( 'ContactEmail' );
		//populate template
		$email->populateTemplate( $data );
		//send mail
		$email->send();
		//return to submitted message

		if ( $this->isAjax ) {
			error_log( "SCF: AJAX" );
			$result = array();


			$result['message'] = $this->SubmitText;
			$result['success'] = 1;

			error_log( "ENCODING TO JSON" );

			echo json_encode( $result );
			die;
		}
		else {
			error_log( "SCF: NON AJAX" );
			Director::redirect( Director::baseURL(). $this->URLSegment . "/?success=1" );
		}
		//

	}

	//The function to test whether to display the Submit Text or not
	public function Success() {
		return isset( $_REQUEST['success'] ) && $_REQUEST['success'] == "1";
	}


	public function ColumnLayout() {
		return 'layout2col';
	}

}
