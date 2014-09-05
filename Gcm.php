<?php

/**
 * GOOGLE CLOUD MESSAGING LIBRARY
 *
 * Originally made for use with CodeIgniter so you'll see
 * the log_message() method being called here
 *
 * @author Chris Bautista <chrisbjr@gmail.com>
 */
class Gcm {

	private $api_key = 'ENTER_API_KEY_HERE';

	private $url = 'https://android.googleapis.com/gcm/send';

	private $response = array('status' => 'pending');

	function __construct()
	{
		// nothing here
	}

	/**
	 * Send a general notification to a device
	 *
	 * @param mixed $gcm_registration_ids Can either be an array of IDs or a single ID
	 * @param string $title
	 * @param string $message
	 */
	public function send_notification($gcm_registration_ids, $title, $message)
	{
		if (function_exists('log_message'))
		{
			log_message('info', 'Sending notification via GCM');
		}

		if (is_array($gcm_registration_ids) == false)
		{
			$gcm_registration_ids = array($gcm_registration_ids);
		}

		$fields = array(
			'registration_ids' => $gcm_registration_ids,
			'data'             => array(
				'title'   => $title,
				'message' => $message
			)
		);

		$response = $this->_send_gcm($fields);

		// Set the response globally
		$this->response = $response;

		if ($response->status == 'ok')
		{
			return TRUE;
		}

		return FALSE;
	}

	private function _send_gcm($fields)
	{
		$fields = json_encode($fields);

		if (function_exists('log_message'))
		{
			log_message('info', 'Sending GCM with the following fields: ' . $fields);
		}

		$headers = array(
			'Authorization: key=' . $this->api_key,
			'Content-Type: application/json'
		);
		// Open connection
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, $this->url);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		// Disabling SSL Certificate support temporarily
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);

		// Execute post
		$result_string = curl_exec($ch);

		// When there is an error with curl
		if (curl_errno($ch))
		{
			if (function_exists('log_message'))
			{
				log_message('error', 'Failed to send push notification');
			}

			return json_encode(array('status' => 'error'));
		}

		// Close connection
		curl_close($ch);

		if (function_exists('log_message'))
		{
			log_message('info', $result_string);
		}

		$result = json_decode($result_string);
		$result->status = 'ok';
		return $result;
	}

	public function get_response()
	{
		return $this->response;
	}

}