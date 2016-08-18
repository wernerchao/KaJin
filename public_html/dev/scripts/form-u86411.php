<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this hosting provider. 
Contact your hosting provider regarding PHP configuration for your site.

PHP file generated by Adobe Muse CC 2015.1.2.344
*/

require_once('form_process.php');

$form = array(
	'subject' => 'corporation Form Submission',
	'heading' => 'New Form Submission',
	'success_redirect' => '',
	'resources' => array(
		'checkbox_checked' => 'Checked',
		'checkbox_unchecked' => 'Unchecked',
		'submitted_from' => 'Form submitted from website: %s',
		'submitted_by' => 'Visitor IP address: %s',
		'too_many_submissions' => 'Too many recent submissions from this IP',
		'failed_to_send_email' => 'Failed to send email',
		'invalid_reCAPTCHA_private_key' => 'Invalid reCAPTCHA private key.',
		'invalid_field_type' => 'Unknown field type \'%s\'.',
		'invalid_form_config' => 'Field \'%s\' has an invalid configuration.',
		'unknown_method' => 'Unknown server request method'
	),
	'email' => array(
		'from' => 'jin@kajinhealth.com',
		'to' => 'jin@kajinhealth.com'
	),
	'fields' => array(
		'Email' => array(
			'order' => 1,
			'type' => 'email',
			'label' => '帳號',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'帳號\' is required.',
				'format' => 'Field \'帳號\' has an invalid email.'
			)
		),
		'custom_U86424' => array(
			'order' => 2,
			'type' => 'string',
			'label' => 'Cell Phone',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'Cell Phone\' is required.'
			)
		),
		'custom_U86415' => array(
			'order' => 3,
			'type' => 'string',
			'label' => 'Company',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'Company\' is required.'
			)
		)
	)
);

process_form($form);
?>
