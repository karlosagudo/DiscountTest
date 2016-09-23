<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 23/09/16
 * Time: 19:43.
 */
namespace tests\ControllerUnit;

use Controllers\ReceiveOrderController;
use Discounts\PreTotal\Switches;
use PHPUnit\Framework\TestCase;
use Silex\Application;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;

class ReceivedOrderControllerTest extends \PHPUnit_Framework_TestCase
{
    public function testIndexAction()
    {
        $app = require __DIR__.'/../../src/app.php';

        $discountMock = $this->getMockBuilder(Switches::class)
            ->disableOriginalConstructor()->getMock();

        unset($app['discounts']);
        $app['discounts'] = ["0" => ["object" => $discountMock]];

        // Initialise the tested class
        $testedClass = new ReceiveOrderController($app);

        //we generate a fake order
        $orderOne = $this->generateOrderOne(10, 4.99);

        // Mock the request
        $requestMock = $this->getMockBuilder(Request::class)
            ->disableOriginalConstructor()
            ->getMock();

        $requestMock
            ->expects($this->atLeastOnce())
            ->method('get')
            ->with('order', null)
            ->willReturn($orderOne);

        //HERE WE TEST WE INVOKE THE METHOD FOR THIS ORDER
        $discountMock->expects($this->once())->method('canApply');

        $testedClass->indexAction($requestMock);
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
}
