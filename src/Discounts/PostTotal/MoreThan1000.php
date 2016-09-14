<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 22:03.
 */
namespace Discounts\PostTotal;

use Discounts\DiscountInterface;

class MoreThan1000 implements DiscountInterface
{
    /**
     * Return a human name of discount.
     *
     * @return string
     */
    public function getHumanName()
    {
        return 'Already bought for over € 1000, gets a discount of 10% on the whole order.';
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
        return $order['total'] > 1000;
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

        $discount = $previousTotal * 0.10;

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
        return 1000;
    }
}
