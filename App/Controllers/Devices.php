<?php

// declare (strict_types = 1);

namespace App\Controllers;

use App\Config;
use Core\View;

class Devices extends Authenticated
{

    // TODO: returns previous page no matter what.
    public function powerAction()
    {
        
        $id = "dummy*light1";

        $client = new \GuzzleHttp\Client();
        // $client->configureDefaults();
        $response = $client->request('GET', Config::IOT_API . '/api/cmd/dummy*light1/power/on');

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
        return $response;
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

        // var_dump($light);
        // die();

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
            $things[] = ['id' => $key, 'name' => $value['name']];
        }

        View::renderTemplate('Devices/my_devices.html.twig', [
            'devices' => $things,
        ]);
    }

    public function philipsLightAction(): void
    {
        View::renderTemplate('Devices/philips_light.html.twig');
    }

    public function parseDevices()
    {
        $devices = file_get_contents(Config::IOT_API . '/api/query');
        
        $devices = json_decode($devices);
        $devices = json_decode(json_encode($devices), true)['devices'];

        return $devices;
    }

    public function getDevice(string $id)
    {
        $device = file_get_contents(Config::IOT_API . '/api/cmd/' . $id . '/query');

        return $device[$id] ?? null;
    }
}
