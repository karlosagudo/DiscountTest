<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 19:27.
 */
namespace Discounts\PreTotal;

use Discounts\DiscountInterface;

/**
 * Class Switches.
 */
class Switches implements DiscountInterface
{
    const CATEGORY_SWITCHES = 2;
    const DIVISOR_NUMBER = 6;

    /**
     * Minimum of DIVISION_NUMBER of type Switches (2).
     *
     * @param array $order
     *
     * @return bool
     */
    public function canApply(array $order)
    {
        foreach ($order['items'] as $item) {
            if ($item['category'] == self::CATEGORY_SWITCHES && $item['quantity'] > self::DIVISOR_NUMBER) {
                return true;
            }
        }

        return false;
    }

    /**
     * For each 5, 6th is free.
     *
     * @param array $order
     * @param bool  $isPost
     *
     * @return array $order
     */
    public function applyDiscount(array $order, $isPost = false)
    {
        $previousTotal = $order['total'];
        $discount = 0;

        foreach ($order['items'] as $key => $item) {
            if ($item['category'] == self::CATEGORY_SWITCHES && $item['quantity'] > self::DIVISOR_NUMBER) {
                $numberOfFreeOnes = floor($item['quantity'] / self::DIVISOR_NUMBER);
                $discount = +$numberOfFreeOnes * $item['unit-price'];
            }
        }
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
     * Order of initialization.
     *
     * @return int
     */
    public static function getInitializeOrder()
    {
        return 1;
    }

    /**
     * @return string
     */
    public function getHumanName()
    {
        return 'For each 5 product of Switches, the 6th is free';
    }
}
