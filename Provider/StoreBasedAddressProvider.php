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

namespace CoreShop\Component\Core\Provider;

use CoreShop\Component\Address\Context\CountryNotFoundException;
use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Core\Context\ShopperContextInterface;
use CoreShop\Component\Core\Model\StoreInterface;
use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Resource\Factory\FactoryInterface;
use CoreShop\Component\Store\Context\StoreNotFoundException;

class StoreBasedAddressProvider implements AddressProviderInterface
{
    public function __construct(private FactoryInterface $addressFactory, private ShopperContextInterface $shopperContext)
    {
    }

    public function getAddress(OrderInterface $cart): ?AddressInterface
    {
        $store = $cart->getStore();
        if ($store instanceof StoreInterface) {
            $address = $this->addressFactory->createNew();

            try {
                $address->setCountry($this->shopperContext->getCountry());
            } catch (StoreNotFoundException) {
                $address->setCountry($store->getBaseCountry());
            } catch (CountryNotFoundException) {
                $address->setCountry($store->getBaseCountry());
            }

            return $address;
        }

        return null;
    }
}
