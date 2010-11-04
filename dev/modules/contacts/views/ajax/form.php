<html>
<head>
<title>Contact form</title>
<link rel="stylesheet" type="text/css" href="<?=base_url()?>application/views/<?php echo $this->system->theme ?>/css/defaults.css">

<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js"></script>
<script type="text/javascript" src="http://api.recaptcha.net/js/recaptcha_ajax.js"></script>
<script type="text/javascript">

$(document).ready(function() { 	
	var RecaptchaOptions = { theme : 'custom' };
 	Recaptcha.create('<?php echo $this->config->item('publickey') ?>', 'recaptcha_image', RecaptchaOptions);
 	Recaptcha.focus_response_field();
	$('#submit_button').click(function() { 
		$.ajax({
			type: 'POST',
			url: '<?php echo site_url('contacts/ajax/response') ?>',
			data: $('form#myform').serialize(),
			dataType: 'json',
			beforeSend: function() {
				var resp_field = $('#recaptcha_response_field').val();
				var name = $('#name').val();
				var email = $('#email').val();
				var message = $('#message').val();
				if (!resp_field[0] || !name[0] || !email[0] || !message[0]) { 
					$('#output').html('All fields are required');
					return false; 
				}
				emailpat = /^([a-z0-9])+([\.a-z0-9_-])*@([a-z0-9])+(\.[a-z0-9_-]+)+$/i;
				if (!emailpat.test(email)) {
					$('#output').html('<?php echo addslashes(__('The e-mail address is not valid.', $module)) ?>'); 
					return false;
				}
			},
			success: function(response) {
				if (response.status == 'success') {
					$('#formcont').html('');
				}
				$('#output').html(response.errmessage);
			}
		});
	});
});
</script>
</head>
<body>
	<h1><?php echo __("Contact form", $module) ?></h1>
	<div id="formcont">				
		<form id="myform">
			<p>
				<label for="name"><?php echo __("Name:", $module) ?></label>
				<input type="text" name="name" id="name" size="30" />
			</p>
			<p>
				<label for="email"><?php echo __("E-mail:", $module) ?></label>
				<input type="text" name="email" id="email" size="30" />
			</p>
			<p>
				<label for="message"><?php echo __("Message:", $module) ?></label>
				<textarea rows="5" cols="30" name="message" id="message"></textarea>
			</p>
			<div id="recaptcha_image"></div>
			<p>
				<input type="text" name="recaptcha_response_field" id="recaptcha_response_field" size="20" />
				<input type="button" value="Submit" id="submit_button" /> 
			</p>
		</form>
	</div>
	<div id="output"></div>
</body>
</html>