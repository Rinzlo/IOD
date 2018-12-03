<?php

declare(strict_types=1);

namespace App\Controllers;


use App\Config;
use Core\View;

class Devices extends Authenticated
{
	public function myDevicesAction(): void
	{
		$devices = file_get_contents(Config::IOT_API.'/api/query');

		$devices = json_decode($devices);
		$devices = json_decode(json_encode( $devices[1]), true);
//		var_dump($devices);
//		exit;
		
		View::renderTemplate('Devices/my_devices.html.twig', $devices);
	}
	
	public function philipsLightAction(): void
	{
		View::renderTemplate('Devices/philips_light.html.twig');
	}
	
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
}