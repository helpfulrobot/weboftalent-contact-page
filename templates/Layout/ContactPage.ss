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
<div class="formBox">
$ContactForm
</div>
<% end_if %>

<script type="text/javascript">

  $(document).ready(function() {
  	load();
  	});

</script>
<% end_cached %>