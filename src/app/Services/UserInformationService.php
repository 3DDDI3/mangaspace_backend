<?php

namespace App\Services;

use App\Models\DeviceToken;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Stevebauman\Location\Facades\Location;
use WhichBrowser\Parser;

class UserInformationService
{
    public function getUserInformation(Request $request, PersonalAccessToken $access_token)
    {
        $browser_info = new Parser($request->header('User-Agent'));
        $location = Location::get($request->ip());

        DeviceToken::create([
            'personal_access_token_id' => $access_token->id,
            'device_type_id' => ,
            'ip_address' => $location->ip,
            'client' => null,
            'model' => $browser_info->device->model,
            'browser'=>$browser_info->browser->name,
            'browser_version' =>$browser_info->browser->version->value,
            'operation_system' =>$browser_info->os->name,
            'operation_version' => $browser_info->os->version->value,
            'country' => $location->countryName,
            'city' => $location->cityName,
        ]);

    }
}
