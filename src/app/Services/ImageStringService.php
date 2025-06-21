<?php

namespace App\Services;

use Illuminate\Support\Collection;

use function PHPSTORM_META\type;

class ImageStringService
{
    /**
     * Undocumented function
     *
     * @param string $extensions
     * @return void
     */
    public static function parseImages(string $extensions)
    {
        $final_array = collect();
        $arr = collect(explode('|', $extensions));

        for ($i = 0; $i < count($arr); $i++) {
            if (!empty($arr[$i])) {
                $elems = collect(explode(',', $arr[$i]));
                foreach ($elems as $sub_item) {
                    switch ($i) {
                        case 0:
                            $final_array->push("{$sub_item}.jpeg");
                            break;

                        case 1:
                            $final_array->push("{$sub_item}.jpg");
                            break;

                        case 2:
                            $final_array->push("{$sub_item}.webp");
                            break;

                        case 3:
                            $final_array->push("{$sub_item}.png");
                            break;
                    }
                }
            }
        }

        $final_array = $final_array->sortBy(fn($item) => $item, SORT_NATURAL)->values();

        return $final_array;
    }

    /**
     * Обновление строки с расширениями (добавление новоого и удаление старого)
     *
     * @param [type] $images строка расширений (*.jpeg|*.jpg|*.webp|*.png)
     * @param [type] $file название файла
     * @return void
     */
    public static function refreshImages($images, $file)
    {
        $fileData  = explode(".", $file);

        $imageExt = collect(explode("|", $images));

        for ($i = 0; $i < count($imageExt); $i++) {
            $images = collect(explode(",", $imageExt[$i]));
            if ($images->search($fileData[0]) !== false) {
                $images->shift($fileData[0]);
                $imageExt[$i] = $images->implode(",");
            }
        }

        switch ($fileData[1]) {
            case 'jpeg':
                $images = collect(explode(",", $imageExt[0]));
                $images[] = $fileData[0];
                $images = $images->filter(function (string $value) {
                    return !empty($value);
                });
                $images = $images->sort();
                $imageExt[0] = $images->implode(",");
                break;

            case 'jpg':
                $images = collect(explode(",", $imageExt[1]));
                $images[] = $fileData[0];
                $images = $images->filter(function (string $value) {
                    return !empty($value);
                });
                $images = $images->sort();
                $imageExt[1] = $images->implode(",");
                break;

            case 'webp':
                $images = collect(explode(",", $imageExt[2]));
                $images[] = $fileData[0];
                $images = $images->filter(function (string $value) {
                    return !empty($value);
                });
                $images = $images->sort();
                $imageExt[2] = $images->implode(",");
                break;

            case 'png':
                $images = collect(explode(",", $imageExt[3]));
                $images[] = $fileData[0];
                $images = $images->filter(function (string $value) {
                    return !empty($value);
                });
                $images = $images->sort();
                $imageExt[3] = $images->implode(",");
                break;
        }

        $extensions = $imageExt->implode("|");

        return $extensions;
    }

    public static function deleteImage($images, $image)
    {
        $imageExt = collect();
        $imageExt->put('extension', "." . explode(".", $image)[1]);
        $image = explode(".", $image)[0];
        $imageExt->put('images', explode("|", $images));

        for ($i = 0; $i < count($imageExt['images']); $i++) {
            $images = collect(explode(",", $imageExt['images'][$i]));
            $key = $images->search($image);
            if ($key !== false) {
                if ($images->count() > 1) {
                    $images->shift($image);
                    $imageExt = $imageExt->map(function ($item) use ($images, $i) {
                        $item[$i] = $images->implode(",");
                        return $item;
                    });
                } else {
                    $imageExt['images'] = array_replace($imageExt['images'], [$i => ""]);
                }
            }
        }

        $imageExt['images'] = implode("|", $imageExt['images']);

        return $imageExt;
    }
}
