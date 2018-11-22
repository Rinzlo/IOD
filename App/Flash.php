<?php
declare(strict_types=1);

namespace App;

/**
 * Flash notification messages: messages for one-time display using the session
 * for storage between requests.
 */
class Flash
{
	
	/**
	 * Success message type
	 * @var string
	 */
	const SUCCESS = 'success';
	
	/**
	 * Information message type
	 * @var string
	 */
	const INFO = 'info';
	
	/**
	 * Warning message type
	 * @var string
	 */
	const WARNING = 'warning';
	
	/**
	 * Add a message
	 *
	 * @param string $message
	 * @param string $type
	 */
	public static function addMessage(string $message, string $type = 'success'): void
	{
		if(! isset($_SESSION['flash_notifications'])){
			$_SESSION['flash_notifications'] = [];
		}
		
		$_SESSION['flash_notifications'][] = [
			'body' => $message,
			'type' => $type
		];
	}
	
	/**
	 * Get all messages
	 *
	 * @return mixed
	 */
	public static function getMessages()
	{
		if(isset($_SESSION['flash_notifications'])){
			$messages = $_SESSION['flash_notifications'];
			unset($_SESSION['flash_notifications']);
			
			return $messages;
		}
	}
}