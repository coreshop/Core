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

namespace CoreShop\Component\Core\Product;

use CoreShop\Component\Address\Model\AddressInterface;
use CoreShop\Component\Order\Model\PurchasableInterface;
use CoreShop\Component\Taxation\Calculator\TaxCalculatorInterface;

interface ProductTaxCalculatorFactoryInterface
{
    public function getTaxCalculator(
        PurchasableInterface $product,
        AddressInterface $address,
        array $context = [],
    ): ?TaxCalculatorInterface;
}
