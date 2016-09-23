<?php

namespace tests\Functional;

use Discounts\PreTotal\Switches;
use Silex\WebTestCase;

/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 23/09/16
 * Time: 19:00.
 */
class OrdersTest extends WebTestCase
{
    public function createApplication()
    {
        $app = require __DIR__.'/../../src/app.php';
        require __DIR__.'/../../config/dev.php';
        require __DIR__.'/../../src/controllers.php';
        $app['session.test'] = true;

        return $this->app = $app;
    }

    public function testDiscountSwitches()
    {
        $order = $this->generateOrderOne(10, 4.99);
        $client = $this->createClient();
        $client->request('POST', '/receive-order', ['order' => $order]);
        $response = json_decode($client->getResponse()->getContent());

        $expectedTotal = $this->expectedTotalOrderOne(10, 4.99);

        $this->assertEquals($response->total, $expectedTotal);

        $randomNumberOfOrders = mt_rand(5, 150);
        $order = $this->generateOrderOne($randomNumberOfOrders, 4.99);

        $client->request('POST', '/receive-order', ['order' => $order]);
        $response = json_decode($client->getResponse()->getContent());

        $expectedTotal = $this->expectedTotalOrderOne($randomNumberOfOrders, 4.99);

        $this->assertEquals($response->total, $expectedTotal);
    }

    /**
     * Generate Order.
     *
     * @param $quantity
     *
     * @return string Order
     */
    private function generateOrderOne($quantity, $unitPrice)
    {
        $total = $quantity * $unitPrice;

        return <<<EOD
{
  "id": "1",
  "customer-id": "1",
  "items": [
    {
      "product-id": "B102",
      "quantity": $quantity,
      "unit-price": $unitPrice,
      "total": $total
    }
  ],
  "total": $total
}
EOD;
    }

    /**
     * @param $quantity
     * @param $unitPrice
     *
     * @return mixed
     */
    private function expectedTotalOrderOne($quantity, $unitPrice)
    {
        $numberOfFreeOnes = floor($quantity / Switches::DIVISOR_NUMBER);
        $discount = +$numberOfFreeOnes * $unitPrice;
        $total = $quantity * $unitPrice;

        return $total - $discount;
    }
}
