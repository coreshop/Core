<?php

declare(strict_types=1);

/*
 * CoreShop
 *
 * This source file is available under two different licenses:
 *  - GNU General Public License version 3 (GPLv3)
 *  - CoreShop Commercial License (CCL)
 * Full copyright and license information is available in
 * LICENSE.md which is distributed with this source code.
 *
 * @copyright  Copyright (c) CoreShop GmbH (https://www.coreshop.org)
 * @license    https://www.coreshop.org/license     GPLv3 and CCL
 *
 */

namespace CoreShop\Component\Core\Payment\Rule\Condition;

use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Payment\Model\PayableInterface;
use CoreShop\Component\Payment\Model\PaymentProviderInterface;
use CoreShop\Component\Shipping\Model\CarrierInterface;
use CoreShop\Component\Shipping\Model\ShippableInterface;
use CoreShop\Component\Payment\Rule\Condition\AbstractConditionChecker;

class CountriesConditionChecker extends AbstractConditionChecker
{
    public function isPaymentProviderRuleValid(
        PaymentProviderInterface $paymentProvider, PayableInterface $payable,  array $configuration, AddressInterface $address = null
    ): bool {
        $country = $address->getCountry();

        return in_array($country->getId(), $configuration['countries']);
    }
}
