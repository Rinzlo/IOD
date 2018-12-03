<?php
/**
 * Created by IntelliJ IDEA.
 * User: Windlo
 * Date: 12/3/2018
 * Time: 12:08 AM
 */

namespace App\Controllers;


use App\Config;

class Devices extends Authenticated
{
	
	public function parseDevices()
	{
		$devices = file_get_contents(Config::IOT_API.'/api/query');
		
		$devices = json_decode($devices);
		$devices = json_decode(json_encode( $devices[1]), true);
		
		foreach ($devices as $name => $caps)
		{
			var_dump('name: ' . $name);
			foreach ($caps as $settings_name => $setting){
				var_dump('    setting name: ' . $settings_name);
//				foreach ($setting as $)
			}
		}

//	    var_dump($devices['dummy-light1']['caps']['bri']);
//	    exit;
	}
	
	public function myDevicesAction(): void
	{
		$devices = file_get_contents(Config::IOT_API.'/api/query');
		
		$devices = json_decode($devices);
		$devices = json_decode(json_encode( $devices[1]), true);
		var_dump($devices);
		exit;
		
		View::renderTemplate('Accounts/my_devices.html.twig', $devices);
	}
}