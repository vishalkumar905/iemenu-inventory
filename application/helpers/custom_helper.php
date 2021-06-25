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
	function p()
	{
		echo "<pre>";
		$numargs = func_num_args();
		$arg_list = func_get_args();

		for ($i = 0; $i < $numargs; $i++) {
			print_r($arg_list[$i]);
		}

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
		
		return urldecode(base64_decode(openssl_decrypt($token, CIPHERING, DECRYPTION_KEY, DECRYPTION_OPTION, DECRYPTION_IV))); 
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

		$token = base64_encode(urldecode($token));
		
		return openssl_encrypt($token, CIPHERING, ENCRYPTION_KEY, ENCRYPTION_OPTION, ENCRYPTION_IV); 
	}
}

if (!function_exists('convertJavascriptDateToPhpDate'))
{
	function convertJavascriptDateToPhpDate($date, $seprator = '/', $placement = ['d', 'm', 'y'])
	{
		if (!empty($date))
		{
			$explodeDate = explode($seprator, $date);
			
			$dayIndex = array_search('d', $placement) === false ? -1 : array_search('d', $placement);
			$monthIndex = array_search('m', $placement) === false ? -1 : array_search('m', $placement);
			$yearIndex = array_search('y', $placement) === false ? -1 : array_search('y', $placement);
			
			if (!empty($explodeDate) && $dayIndex >= 0 && $monthIndex >= 0 && $yearIndex >= 0)
			{
				return date('Y-m-d', strtotime(sprintf('%s-%s-%s', $explodeDate[$yearIndex], $explodeDate[$monthIndex], $explodeDate[$dayIndex])));
			}
			else
			{
				throw new Exception('Date is invalid');
			}
		}
	}
}

if (!function_exists('truncateNumber'))
{
	function truncateNumber( $number, $precision = 3) {
		// Zero causes issues, and no need to truncate
		if ( 0 == floatval($number) ) {
			return $number;
		}
		
		// Are we negative?
		$negative = $number / abs($number);
		// Cast the number to a positive to solve rounding
		$number = abs($number);
		// Calculate precision number for dividing / multiplying
		$precision = pow(10, $precision);
		
		// Run the math, re-applying the negative value to ensure returns correctly negative / positive
		return floor( $number * $precision ) / $precision * $negative;
	}
}
?>