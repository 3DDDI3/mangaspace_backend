<?php

namespace App\Services;

use App\Models\DeviceToken;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use Laravel\Sanctum\PersonalAccessToken;
use Stevebauman\Location\Facades\Location;
use WhichBrowser\Parser;

/**
 * Информации о девайсе пользователя
 */
class UserDeviceInformation
{
    /**
     * Получение информации о устройстве пользователя 
     *
     * @param Request $request
     * @param PersonalAccessToken $access_token
     * @return void
     */
    public function getDeviceInformation(Request $request, PersonalAccessToken $access_token)
    {
        $location = Location::get($_SERVER['HTTP_X_REAL_IP']);

        $ip = !$location ? "" : $_SERVER['HTTP_X_REAL_IP'];

        if (config('app.env') == "local") $location = null;

        $browserInfo = new Parser($request->header('User-Agent'));

        $deviceType = null;

        switch ($browserInfo->device->type) {
            case 'mobile':
                $deviceType = DeviceType::query()
                    ->where(['type' => 'Смартфон'])
                    ->first('id')
                    ->id;
                break;

            case 'desktop':
                $deviceType = DeviceType::query()
                    ->where(['type' => 'Компьютер'])
                    ->first('id')
                    ->id;
                break;

            case 'desktop':
                $deviceType = DeviceType::query()
                    ->where(['type' => 'Телевизор'])
                    ->first('id')
                    ->id;
                break;
        }

        DeviceToken::create([
            'personal_access_token_id' => $access_token->id,
            'device_type_id' => $deviceType,
            'ip_address' => $ip,
            'client' => $browserInfo->browser->type,
            'model' => $browserInfo->device->model,
            'browser' => $browserInfo->browser->name,
            'browser_version' => $browserInfo->browser->version->value,
            'operation_system' => $browserInfo->os->name,
            'operation_version' => $browserInfo->os->version->value,
            'country' => $location?->countryName,
            'city' => $location?->cityName,
        ]);
    }
}
