<?php
/**
 * Prowl
 *
 * This class provides a path to the prowlapp.com api.
 *
 * @author Bart Moorman <bart.moorman at id10t dot us>
 * @version 1.00
 * @package Prowl
 */
class Prowl
{
	private $api = 'https://api.prowlapp.com/publicapi/';

	private $apikeys = array();
	private $priority = 0;
	private $url = null;
	private $application = null;
	private $event = null;
	private $description = null;

	private $debug = false;
	private $error = false;

	private $remaining = 0;
	private $resetdate = 0;

	public function addApiKey($apikey)
	{
		if(strlen($apikey) != 40):
			echo 'apikey must be exactly 40 characters!' . PHP_EOL;
			return false;
		else:
			$this->apikeys[] = $apikey;
			return true;
		endif;
	}
	public function setPriority($priority)
	{
		if($priority > 2 || $priority < -2):
			echo 'priority must be between 2 and -2!' . PHP_EOL;
			return false;
		else:
			$this->priority = $priority;
			return true;
		endif;
	}
	public function setUrl($url)
	{
		if(strlen($url) > 512):
			echo 'url must be 512 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->url = $url;
			return true;
		endif;
	}
	public function setApplication($application)
	{
		if(strlen($application) > 256):
			echo 'application must be 256 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->application = $application;
			return true;
		endif;
	}
	public function setEvent($event)
	{
		if(strlen($event) > 1024):
			echo 'event must be 1,024 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->event = $event;
			return true;
		endif;
	}
	public function setDescription($description)
	{
		if(strlen($description) > 10000):
			echo 'description must be 10,000 characters or less!' . PHP_EOL;
			return false;
		else:
			$this->description = str_replace('\n', PHP_EOL, $description);
			return true;
		endif;
	}

	public function setDebug($debug)
	{
		$this->debug = filter_var($debug, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
	}

	public function getRemaining()
	{
		if(!empty($this->remaining)):
			return $this->remaining;
		else:
			return false;
		endif;
	}
	public function getResetDate($format = null)
	{
		if(!empty($this->resetdate)):
			if(!empty($format)):
				return date($format, $this->resetdate);
			else:
				return $this->resetdate;
			endif;
		else:
			return false;
		endif;
	}

	private function prepare()
	{
		if(!empty($this->apikeys)):
			$this->fields['apikey'] = implode(',', $this->apikeys);
		else:
			echo 'apikey is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->priority)):
			$this->fields['priority'] = $this->priority;
		endif;

		if(!empty($this->url)):
			$this->fields['url'] = $this->url;
		endif;

		if(!empty($this->application)):
			$this->fields['application'] = $this->application;
		else:
			echo 'application is required!' . PHP_EOL;
			$this->error = true;
		endif;

		if(!empty($this->event) || !empty($this->description)):
			if(!empty($this->event)):
				$this->fields['event'] = $this->event;
			endif;

			if(!empty($this->description)):
				$this->fields['description'] = $this->description;
			endif;
		else:
			echo 'event or description is required!' . PHP_EOL;
			$this->error = true;
		endif;

		return $this->error ? false : true;
	}
	public function send()
	{
		if($this->prepare()):
			$ch = curl_init();

			$options = array(
				CURLOPT_FOLLOWLOCATION => true,
				CURLOPT_POST => true,
				CURLOPT_RETURNTRANSFER => true,
				CURLOPT_POSTFIELDS => $this->fields,
				CURLOPT_URL => $this->api . 'add'
			);

			curl_setopt_array($ch, $options);
			$response = curl_exec($ch);
			curl_close($ch);

			$xml = new SimpleXMLElement($response);

			if($xml->success):
				$this->remaining = $xml->success->attributes()->remaining;
				$this->resetdate = $xml->success->attributes()->resetdate;
				return true;
			else:
				echo $xml->error->attributes()->code . ' ' . $xml->error . PHP_EOL;
				return false;
			endif;
		else:
			echo 'unable to send notification!' . PHP_EOL;
			return false;
		endif;
	}
}
?>
