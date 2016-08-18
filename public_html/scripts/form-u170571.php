<?php 
/* 	
If you see this text in your browser, PHP is not configured correctly on this hosting provider. 
Contact your hosting provider regarding PHP configuration for your site.

PHP file generated by Adobe Muse CC 2015.1.2.344
*/

require_once('form_process.php');

$form = array(
	'subject' => 'Kajin Health Form Submission',
	'heading' => 'New Form Submission',
	'success_redirect' => '../send.php',
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
			'order' => 2,
			'type' => 'email',
			'label' => 'Email(必需)',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'Email(必需)\' is required.',
				'format' => 'Field \'Email(必需)\' has an invalid email.'
			)
		),
		'custom_U170578' => array(
			'order' => 1,
			'type' => 'string',
			'label' => '您在煩惱什麼呢？',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'您在煩惱什麼呢？\' is required.'
			)
		),
		'custom_U170582' => array(
			'order' => 4,
			'type' => 'string',
			'label' => '電話(必需)',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'電話(必需)\' is required.'
			)
		),
		'custom_U170573' => array(
			'order' => 3,
			'type' => 'string',
			'label' => '所在地/國碼(必需)',
			'required' => true,
			'errors' => array(
				'required' => 'Field \'所在地/國碼(必需)\' is required.'
			)
		),
		'custom_U170592' => array(
			'order' => 5,
			'type' => 'radiogroup',
			'label' => '您的年齡',
			'required' => true,
			'optionItems' => array(
				'25 歲以下',
				'25 - 35 歲',
				'35 - 55 歲',
				'55 歲以上'
			),
			'errors' => array(
				'required' => 'Field \'您的年齡\' is required.',
				'format' => 'Field \'您的年齡\' has an invalid value.'
			)
		)
	)
);

process_form($form);
?>
