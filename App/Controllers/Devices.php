<?php

// declare (strict_types = 1);

namespace App\Controllers;

use App\Config;
use Core\View;
use App\Flash;

class Devices extends Authenticated
{

    // TODO: returns previous page no matter what.
    public function powerAction()
    {
        
        $id = "dummy*light1";

        // $client = new \GuzzleHttp\Client();
        // $response = $client->request('GET', Config::IOT_API . '/api/cmd/'.$id.'/power/on');

        // echo $response->getStatusCode(); # 200
        // echo $response->getHeaderLine('content-type'); # 'application/json; charset=utf8'
        // echo $response->getBody(); # '{"id": 1420053, "name": "guzzle", ...}'

        # Send an asynchronous request.
        // $request = new \GuzzleHttp\Psr7\Request('GET', Config::IOT_API . '/api/cmd/'.$id.'/power/on');
        // $promise = $client->sendAsync($request)->then(function ($response) {
        //     // echo $response->getBody();
        // });
        // $promise->wait();

        // echo $response->getBody();

        $device = file_get_contents(Config::IOT_API . '/api/cmd/'.$id.'/power/on');
        
        $device = json_decode($device);
        $device = json_encode(json_decode(json_encode($device), true)['devices'][$id]);
        // var_dump($device);
        // die();
        return $device;
    }

    public function usageStatisticsAction(): void
    {
        View::renderTemplate('Devices/usageStatistics.html.twig');
    }

    /**
     * takes in an 'id' as its route_params
     *
     * @throws \Twig_Error_Loader
     * @throws \Twig_Error_Runtime
     * @throws \Twig_Error_Syntax
     * 
     * @return void
     */
    public function myLightAction(): void
    {

        $id = $this->route_params['id'];

        $devices = $this->parseDevices();
        
        $light = $devices[$id];
        $light['id'] = $id;

        View::renderTemplate('Devices/my_light.html.twig', [
            'light' => $light,
            'iotUrl' => Config::IOT_API . '/api/cmd/' . $id,
        ]);
    }

    public function myDevicesAction(): void
    {
        $devices = $this->parseDevices();
        // var_dump($devices);
        // exit();
        $things = [];

        foreach ($devices as $key => $value) {
            if(array_key_exists('auth', $value['caps'])) {

                // $things[] = ['id' => $key, 'name' => $value['name']];
                if(Config::IOT_DEBUG)
                Flash::addMessage('got one!');
                $things[] = ['id' => $key, 'name' => $value['name'], 'auth' => null];
            } else {
                $things[] = ['id' => $key, 'name' => $value['name']];
            }
        }
        //'api/query/<device_id>/cmd/auth'
        View::renderTemplate('Devices/my_devices.html.twig', [
            'devices' => $things,
            'iotUrl' => Config::IOT_API
        ]);
    }

    public function philipsLightAction(): void
    {
        View::renderTemplate('Devices/philips_light.html.twig');
    }

    public function parseDevices()
    {
        $devices = file_get_contents(Config::IOT_API . '/api/query');
        
        $devices = json_decode($devices, true)['devices'];

        $needs_auth = array_walk_recursive($devices, function($v, $k) use (&$ids) {
            if ($k === 'id') {
                return true;
            }
        });

        foreach($devices as $name => $funcs) {
            $can_name = "[".json_encode($name)."]: ";
            $func_str = "";
            foreach($funcs as $func_key => $func_val) {
                
                $func_str .= json_encode($func_key).json_encode($func_val);
                // break;
            }
            
            if(Config::IOT_DEBUG) {
                Flash::addMessage($can_name.$func_str, "info");
            }

            
        }

        return $devices;
    }

    public function getDevice(string $id)
    {
        $device = file_get_contents(Config::IOT_API . '/api/cmd/' . $id . '/query');

        return $device[$id] ?? null;
    }
}
