<% cached 'contactPage', ID, LastEdited,Success %>
<h1>$Title</h1>

<% if Success %>
<div class="alert success">
<button type="button" class="close" data-dismiss="alert">×</button>
$SubmitText
</div>
<% end_if %>

$Content

<div class="row">

<div class="span7">

<div class="well">
<h3><% _t('ContactPage.ADDRESS', '(TH) Address') %></h3>
$ContactAddress
<br/>
<br/>

<h3><% _t('ContactPage.TELEPHONE_AND_EMAIL', 'Telephone, Fax and Email') %></h3>
<% _t('ContactPage.CONTACT_TELEPHONE_NUMBER','Telephone number') %>: $ContactTelephoneNumber

<br/><% _t('ContactPage.CONTACT_FAX_NUMBER','Fax number') %>: $ContactFaxNumber

<br/><% _t('ContactPage.CONTACT_EMAIL_ADDRESS','Email Address') %>: $ContactEmailAddress

</div>
</div>

<div class="span5">
$Map
</div>
</div>


<% if Success %>
<% else %>
<br/>

<% end_if %>

<p class="note">
Please override this template in your own theme and do the following
<ul>
<li class="note">* Move the declaration of jQuery to your Page template</li>
<li class="note">* Move the definition of the css class .fullWidthMap to your main stylesheet as opposed to inline in the HTML of this template</li>
</ul>
</p>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<style>
.fullWidthMap {
	height: 400px;
	width: 50%;
}

.note, .note  li {
	font-style: italic;
	color: red;
	}
</style>

<div class="formBox">
$ContactForm
</div>


<script type="text/javascript">


     (function($) {
       $(document).ready(function() {
  			load();
  		});
     })(jQuery);

</script>
<% end_cached %>