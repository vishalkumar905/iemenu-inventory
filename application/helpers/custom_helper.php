<?php

if (!function_exists('responseJson'))
{
	function responseJson($isSuccess, $message, $data, $wrapper = true)
	{
		$ci = &get_instance();
	
		if ($wrapper)
		{
			$jsonData['response'] = $data;
			$jsonData['status'] = $isSuccess;
			$jsonData['message'] = $message;
		}
		else
		{
			$jsonData = $data;
		}
		$ci->output->set_content_type('application/json')->set_output(json_encode($jsonData));
	}
}

if (!function_exists('showAlertMessage'))
{
	function showAlertMessage($message, $type)
	{
		if (!empty($message))
		{
			$alertMessageHtml = sprintf('
				<div class="alert alert-%s" id="alertMessage">
					<button type="button" aria-hidden="true" class="close">
						<i class="material-icons">close</i>
					</button>
					<span>%s</span>
				</div>
			', $type, $message);

			return $alertMessageHtml;
		}
	}
}

if (!function_exists('p'))
{
	function p($data)
	{
		echo "<pre>";
		print_r($data);
		echo "<pre>";
		die();
	}
}

if (!function_exists('decryptToken'))
{
	function decryptToken($token)
	{
		if (empty($token))
		{
			return false;
		}

		return urldecode(openssl_decrypt($token, CIPHERING, DECRYPTION_KEY, DECRYPTION_OPTION, DECRYPTION_IV)); 
	}
}

if (!function_exists('encryptToken'))
{
	function encryptToken($token)
	{
		if (empty($token))
		{
			return false;
		}

		return openssl_encrypt($token, CIPHERING, ENCRYPTION_KEY, ENCRYPTION_OPTION, ENCRYPTION_IV); 
	}
}
?>