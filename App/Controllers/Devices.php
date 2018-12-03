<?php

declare(strict_types=1);

namespace App\Controllers;


use App\Config;
use Core\View;

class Devices extends Authenticated
{
	public function myLightAction(): void
	{
		
		$id = $this->route_params['id'];
		
		$devices = $this->parseDevices();
		
		$things = [];
		foreach ($devices as $key => $value){
			$things[] = ['id' => $key, 'name' => $value['name']];
		}
		
		$light = $devices[$id];
		$light['id'] = $id;
		
		View::renderTemplate('Devices/my_light.html.twig', [
			'light' => $light,
			'url' => Config::IOT_API.'/api/cmd/'.$id,
		]);
	}
	
	public function myDevicesAction(): void
	{
		$devices = $this->parseDevices();
		
		$things = [];
		foreach ($devices as $key => $value){
			$things[] = ['id' => $key, 'name' => $value['name']];
		}
//		var_dump($devices);
//		exit;
		
		View::renderTemplate('Devices/my_devices.html.twig', [
			'devices' => $things
		]);
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
		
		return $devices;
	}
}