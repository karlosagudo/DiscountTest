<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 18:20.
 */
namespace Controllers;

use Silex\Application;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Traits\initializer;

/**
 * Class ReceiveOrderController.
 */
class ReceiveOrderController
{
    use initializer;

    /**
     * Index constructor.
     *
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->initialize($app);
    }

    /**
     * @param Request     $request
     * @param Application $app
     */
    public function indexAction(Request $request)
    {
        $order = json_decode($request->get('order', null), true);
        if (!$order) {
            $this->app->abort('400', 'You should provide a parameter in a post with a json on it');
        }

        $order = $this->preProcessOrder($order);

        foreach ($this->app['discounts'] as $possibleDiscount) {
            $discount = $possibleDiscount['object'];

            if ($discount->canApply($order)) {
                $order = $discount->applyDiscount($order, $possibleDiscount['post']);
            }
        }

        return new JsonResponse($order);
    }

    /**
     * Put category on each product, reading from initalizer products-categories.
     *
     * @param array $order
     *
     * @return array
     */
    private function preProcessOrder(array $order)
    {
        foreach ($order['items'] as $key => $item) {
            $order['items'][$key]['category'] = $this->data['products-categories'][$item['product-id']];
        }

        return $order;
    }
}
