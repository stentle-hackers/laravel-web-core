<?php

/**
 * Created by PhpStorm.
 * User: giuseppetoto
 * Date: 10/07/15
 * Time: 10:56
 */

namespace Stentle\LaravelWebcore\Models;

use Stentle\LaravelWebcore\Facades\ClientHttp;
use Stentle\LaravelWebcore\Models\Product;


/**
 * Class ProductFeed
 * @package Stentle\LaravelWebcore\Models
 */
class LofProductFeed extends ProductFeed
{
    public $attributeVariants;

    public static function searchForProd($filter = array())
    {
        $options = [];

        if (empty($filter)) {
            $filter = self::createFilter([], [], [], 1, 100);
        }
        $options['headers']['Accept'] = 'application/stentle.api-v0.2+json';
        $options['json'] = $filter; //filters

        $response = ClientHttp::post('catalog', $options);

        if ($response->getStatusCode() >= 400)
            throw new \Exception("catalog search request failed with code: " . $response->getStatusCode());
        else
            $json = json_decode($response->getBody()->getContents(), true);

        $products = [];
        $items = $json['data']['result']['items'];


        foreach ($json['data']['result']['items'] as $item) {

            $p = (new LofProductFeed());
            $p->setInfo($item);
            $products[] = $p;
        }

        $json['data']['result']['items'] = $products;
        return $json;
    }
}
