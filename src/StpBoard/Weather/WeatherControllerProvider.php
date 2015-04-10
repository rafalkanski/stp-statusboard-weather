<?php

namespace StpBoard\Weather;

use Cmfcmf\OpenWeatherMap;
use Silex\Application;
use Silex\ControllerCollection;
use Silex\ControllerProviderInterface;
use StpBoard\Base\BoardProviderInterface;
use StpBoard\Base\TwigTrait;

class WeatherControllerProvider implements ControllerProviderInterface, BoardProviderInterface
{
    use TwigTrait;

    /**
     * Returns route prefix, starting with "/"
     *
     * @return string
     */
    public static function getRoutePrefix()
    {
        return '/weather';
    }

    /**
     * @param Application $app An Application instance
     *
     * @return ControllerCollection A ControllerCollection instance
     */
    public function connect(Application $app)
    {
        $this->initTwig(__DIR__ . '/views');
        $controllers = $app['controllers_factory'];

        $controllers->get(
            '/',
            function (Application $app) {
                $request = $app['request'];

                $city = $request->get('city');
                if (empty($city)) {
                    return $this->twig->render('error.html.twig');
                }

                $openWeatherMap = new OpenWeatherMap();
                try {
                    $weather = $openWeatherMap->getWeather($city, 'metric');
                } catch (\Exception $e) {
                    return $this->twig->render('error.html.twig');
                }

                return $this->twig->render(
                    'weather.html.twig',
                    [
                        'id' => $request->get('id'),
                        'temperature' => (int)$weather->temperature->now->getValue(),
                        'icon' => $weather->weather->icon
                    ]
                );
            }
        );

        return $controllers;
    }
}
