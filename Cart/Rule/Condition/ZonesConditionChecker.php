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

namespace CoreShop\Component\Core\Cart\Rule\Condition;

use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Address\Model\ZoneInterface;
use CoreShop\Component\Core\Model\CountryInterface;
use CoreShop\Component\Customer\Model\CustomerInterface;
use CoreShop\Component\Order\Cart\Rule\Condition\AbstractConditionChecker;
use CoreShop\Component\Order\Model\CartPriceRuleInterface;
use CoreShop\Component\Order\Model\CartPriceRuleVoucherCodeInterface;
use CoreShop\Component\Order\Model\OrderInterface;

final class ZonesConditionChecker extends AbstractConditionChecker
{
    public function isCartRuleValid(OrderInterface $cart, CartPriceRuleInterface $cartPriceRule, ?CartPriceRuleVoucherCodeInterface $voucher, array $configuration): bool
    {
        if (!$cart->getCustomer() instanceof CustomerInterface) {
            return false;
        }

        if (!$cart->getInvoiceAddress() instanceof AddressInterface) {
            return false;
        }

        if (!$cart->getInvoiceAddress()->getCountry() instanceof CountryInterface) {
            return false;
        }

        if (!$cart->getInvoiceAddress()->getCountry()->getZone() instanceof ZoneInterface) {
            return false;
        }

        return in_array($cart->getInvoiceAddress()->getCountry()->getZone()->getId(), $configuration['zones']);
    }
}
