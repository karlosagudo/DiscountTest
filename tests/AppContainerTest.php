<?php

namespace tests;

use Discounts\DiscountInterface;
use Silex\WebTestCase;

/**
 * Class ControllersTest
 * Simply test App loaded Data
 * @package tests
 */
class AppContainerTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../src/app.php';
        require __DIR__.'/../config/dev.php';
        require __DIR__.'/../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }

    public function testAppHasLoadedData()
    {
        $this->assertArrayHasKey('customers', $this->app['data'], 'We have loaded the customer files');
        $this->assertArrayHasKey('products', $this->app['data'], 'We have loaded the product files');
        $this->assertArrayHasKey('discounts', $this->app, 'We have loaded the discounts');
        $randomDiscountKey = mt_rand(1, count($this->app['discounts']))-1;
        $this->assertInstanceOf(
            DiscountInterface::class,
            $this->app['discounts'][$randomDiscountKey]["object"],
            'The discounts extends an interface'
        );
    }
}
