<?php
$errors = '';
$myemail = 'admin@rust4fun.io';//<-----Put Your email address here.
if(empty($_POST['email'])  ||
   empty($_POST['pluginurl']) ||
   empty($_POST['subject']))
{
    $errors .= "\n Error: all fields are required";
}
$email_address = $_POST['email'];
$pluginurl = $_POST['pluginurl'];
$reason = $_POST['subject'];
if (!preg_match(
"/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/i",
$email_address))
{
    $errors .= "\n Error: Invalid email address";
}

if( empty($errors))
{
$to = $myemail;
$email_subject = "Contact form submission: $email_adress";
$email_body = "New plugin request has arrived. ".
" Here are the details:\n Email: $email_adress \n ".
"Plugin: $pluginurl\n Reason: \n $reason";
$headers = "From: $email_address\n";
$headers .= "Reply-To: $email_address";
mail($to,$email_subject,$email_body,$headers);
//redirect to the 'thank you' page
header('Location: contact-form-thank-you.html');
}
?>