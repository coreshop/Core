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

namespace CoreShop\Component\Core\Cart\Rule\Applier;

use CoreShop\Component\Order\Model\OrderInterface;
use CoreShop\Component\Order\Model\PriceRuleItemInterface;

interface CartRuleApplierInterface
{
    public function applyDiscount(
        OrderInterface $cart,
        PriceRuleItemInterface $cartPriceRuleItem,
        int $discount,
        bool $withTax = false,
    ): void;

    public function applySurcharge(
        OrderInterface $cart,
        PriceRuleItemInterface $cartPriceRuleItem,
        int $discount,
        bool $withTax = false,
    ): void;
}
