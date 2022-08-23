<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GPLv3 and CCL
 */

declare(strict_types=1);

namespace CoreShop\Component\Core\Order\Processor;

use CoreShop\Component\Core\Model\OrderItemInterface;
use CoreShop\Component\Order\Calculator\PurchasableCalculatorInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Processor\CartItemProcessorInterface;
use CoreShop\Component\Order\Processor\CartProcessorInterface;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\ProductQuantityPriceRules\Detector\QuantityReferenceDetectorInterface;
use CoreShop\Component\ProductQuantityPriceRules\Exception\NoPriceFoundException;
use CoreShop\Component\ProductQuantityPriceRules\Exception\NoRuleFoundException;
use CoreShop\Component\ProductQuantityPriceRules\Model\QuantityRangePriceAwareInterface;

final class CartItemsProcessor implements CartProcessorInterface
{
    public function __construct(private PurchasableCalculatorInterface $productPriceCalculator, private QuantityReferenceDetectorInterface $quantityReferenceDetector, private CartItemProcessorInterface $cartItemProcessor, private CartContextResolverInterface $cartContextResolver)
    {
    }

    public function process(OrderInterface $cart): void
    {
        $context = $this->cartContextResolver->resolveCartContext($cart);

        $subtotalGross = 0;
        $subtotalNet = 0;

        /**
         * @var OrderItemInterface $item
         */
        foreach ($cart->getItems() as $item) {
            if ($item->getIsGiftItem()) {
                $this->cartItemProcessor->processCartItem(
                    $item,
                    0,
                    0,
                    0,
                    0,
                    $context
                );

                continue;
            }

            $product = $item->getProduct();

            if ($item->hasUnitDefinition()) {
                $context['unitDefinition'] = $item->getUnitDefinition();
            } else {
                unset($context['unitDefinition']);
            }

            $context['cartItem'] = $item;

            $itemPrice = $this->productPriceCalculator->getPrice($product, $context, true);

            // respect item quantity factor
            if ($product instanceof ProductInterface && is_numeric($product->getItemQuantityFactor()) && $product->getItemQuantityFactor() > 1) {
                $itemPrice = (int)round($itemPrice / $product->getItemQuantityFactor());
            }

            if ($product instanceof QuantityRangePriceAwareInterface) {
                try {
                    $itemPrice = $this->quantityReferenceDetector->detectQuantityPrice(
                        $product,
                        $item->getQuantity(),
                        $itemPrice,
                        $context
                    );
                } catch (NoRuleFoundException) {
                } catch (NoPriceFoundException) {
                }
            }

            $itemPriceWithoutDiscount = $this->productPriceCalculator->getPrice($product, $context);
            $itemRetailPrice = $this->productPriceCalculator->getRetailPrice($product, $context);
            $itemDiscountPrice = $this->productPriceCalculator->getDiscountPrice($product, $context);
            $itemDiscount = $this->productPriceCalculator->getDiscount($product, $context, $itemPriceWithoutDiscount);

            if (null === $item->getCustomItemDiscount()) {
                $item->setCustomItemDiscount(0);
            }

            if ($item->getCustomItemPrice()) {
                $itemPrice = $item->getCustomItemPrice();
            } else {
                $item->setCustomItemPrice(0);
            }

            if ($item->getCustomItemDiscount() > 0) {
                $itemPrice = (int)round((100 - $item->getCustomItemDiscount()) / 100 * $itemPrice);
            }

            $this->cartItemProcessor->processCartItem(
                $item,
                $itemPrice,
                $itemRetailPrice,
                $itemDiscountPrice,
                $itemDiscount,
                $context
            );

            $subtotalGross += $item->getTotal(true);
            $subtotalNet += $item->getTotal(false);
        }

        $cart->setSubtotal($subtotalGross, true);
        $cart->setSubtotal($subtotalNet, false);

        $cart->recalculateAdjustmentsTotal();
    }
}
