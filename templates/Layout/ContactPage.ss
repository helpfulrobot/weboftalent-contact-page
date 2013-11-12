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

<% if ContactAddress %>
<h3><% _t('ContactPage.ADDRESS', '(TH) Address') %></h3>
$ContactAddress
<% end_if %>

<% if HasTelecomAddress %>
<h3><% _t('ContactPage.TELEPHONE_AND_EMAIL', 'Telephone, Fax and Email') %></h3>
<table class="table table-striped">
<% if ContactTelephoneNumber %>
<tr><th><% _t('ContactPage.CONTACT_TELEPHONE_NUMBER','Telephone number') %></th><td>$ContactTelephoneNumber</td></tr>
<% end_if %>
<% if ContactFaxNumber%>
<tr><th><% _t('ContactPage.CONTACT_FAX_NUMBER','Fax number') %></th><td>$ContactFaxNumber</td></tr>
<% end_if %>
<% if ContactEmailAddress %>
<tr><th><% _t('ContactPage.CONTACT_EMAIL_ADDRESS','Email Address') %></th><td>$ContactEmailAddress</td></tr>
<% end_if %>
</table>
<% end_if %>


<% if HasSocialMedia %>
<h3>Social Media</h3>
<table class="table table-striped">
<% if Twitter %><tr><th>Twitter</th><td><a href="https://twitter.com/$Twitter">@$Twitter</a></td></tr><% end_if %>
<% if Facebook %><tr><th>Facebook</th><td><a href="$Facebook">$Facebook</a></td></tr><% end_if %>
<% end_if %>
</table>
</div>


<div class="span5">
$BasicMap
</div>
</div>


<% if Success %>
<% else %>
<p>You can also contact us via this contact form</p>
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