<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 21:35.
 */
namespace Discounts\PreTotal;

use Discounts\DiscountInterface;

/**
 * Class Tools.
 */
class Tools implements DiscountInterface
{
    const CATEGORY_TOOLS = 1;

    /**
     * Return a human name of discount.
     *
     * @return string
     */
    public function getHumanName()
    {
        return 'Two or more products of category "Tools" (id 1), you get a 20% discount on the cheapest product';
    }

    /**
     * Should return a boolean, if the order can apply the discount or not.
     *
     * @param array $order
     *
     * @return bool
     */
    public function canApply(array $order)
    {
        $quantity = 0;
        foreach ($order['items'] as $item) {
            if ($item['category'] == self::CATEGORY_TOOLS) {
                $quantity += $item['quantity'];
            }
        }

        return $quantity >= 2 ? true : false;
    }

    /**
     * Apply the discount and add info into the order.
     *
     * @param array $order
     *
     * @return array $order
     */
    public function applyDiscount(array $order, $isPost)
    {
        $previousTotal = $order['total'];
        $discount = 0;

        $cheapestProduct = null;
        foreach ($order['items'] as $item) {
            if ($item['category'] == self::CATEGORY_TOOLS) {
                if (!isset($cheapestProduct) || $item['unit-price'] < $cheapestProduct) {
                    $cheapestProduct = $item['unit-price'];
                }
            }
        }
        $discount = 0.20 * $cheapestProduct;

        $order['total'] -= $discount;

        $initializeOrder = self::getInitializeOrder();
        $order['discounts'][$initializeOrder] =
            [
                'human' => $this->getHumanName(),
                'previousTotal' => $previousTotal,
                'discount' => $discount,
                'post' => $isPost,
            ];

        return $order;
    }

    /**
     * Static function to return the order in wich we apply the discounts.
     *
     * @return mixed
     */
    public static function getInitializeOrder()
    {
        return 2;
    }
}
