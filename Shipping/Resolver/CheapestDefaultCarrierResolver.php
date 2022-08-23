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

namespace CoreShop\Component\Core\Shipping\Resolver;

use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Order\Cart\CartContextResolverInterface;
use CoreShop\Component\Shipping\Calculator\CarrierPriceCalculatorInterface;
use CoreShop\Component\Shipping\Exception\UnresolvedDefaultCarrierException;
use CoreShop\Component\Shipping\Model\CarrierInterface;
use CoreShop\Component\Shipping\Model\ShippableInterface;
use CoreShop\Component\Shipping\Resolver\CarriersResolverInterface;
use CoreShop\Component\Shipping\Resolver\DefaultCarrierResolverInterface;

final class CheapestDefaultCarrierResolver implements DefaultCarrierResolverInterface
{
    public function __construct(private CarriersResolverInterface $carriersResolver, private CarrierPriceCalculatorInterface $carrierPriceCalculator, private CartContextResolverInterface $cartContextResolver)
    {
    }

    public function getDefaultCarrier(ShippableInterface $shippable, AddressInterface $address): CarrierInterface
    {
        $carriers = $this->carriersResolver->resolveCarriers($shippable, $address);

        if (!$shippable instanceof OrderInterface) {
            throw new UnresolvedDefaultCarrierException();
        }

        if (empty($carriers)) {
            throw new UnresolvedDefaultCarrierException();
        }

        uasort($carriers, function (CarrierInterface $a, CarrierInterface $b) use ($shippable, $address) {
            $aPrice = $this->carrierPriceCalculator->getPrice($a, $shippable, $address, $this->cartContextResolver->resolveCartContext($shippable));
            $bPrice = $this->carrierPriceCalculator->getPrice($b, $shippable, $address, $this->cartContextResolver->resolveCartContext($shippable));

            return $aPrice > $bPrice ? 1 : -1;
        });

        return reset($carriers);
    }
}
