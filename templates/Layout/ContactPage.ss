<h1>$Title</h1>


<% if Success %>
<div class="alert success">
$SubmitText
</div>
<% end_if %>

$Content

<div class="addressBox">
<h3 class="first">Address</h3>
$ContactAddress

<% if ContactTelephoneNumber %>
<h3>Telephone</h3>
$ContactTelephoneNumber
<% end_if %>

<% if ContactEmailAddress %>
<h3>Email Address</h3>
$ContactEmailAddress
<% end_if %>
</div>


<% if Success %>

<% else %>
<div class="formBox">
$ContactForm
</div>
<% end_if %>