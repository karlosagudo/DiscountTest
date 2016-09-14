<?php
/**
 * Created by PhpStorm.
 * User: karlos
 * Date: 14/09/16
 * Time: 19:22.
 */
namespace Discounts;

/**
 * Interface DiscountInterface.
 */
interface DiscountInterface
{
    /**
     * Return a human name of discount.
     *
     * @return string
     */
    public function getHumanName();

    /**
     * Should return a boolean, if the order can apply the discount or not.
     *
     * @param array $order
     *
     * @return bool
     */
    public function canApply(array $order);

    /**
     * Apply the discount and add info into the order.
     *
     * @param array $order
     *
     * @return array $order
     */
    public function applyDiscount(array $order, $isPost);

    /**
     * Static function to return the order in wich we apply the discounts.
     *
     * @return mixed
     */
    public static function getInitializeOrder();
}
