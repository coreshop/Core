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

namespace CoreShop\Component\Core\Product\Rule\Condition;

use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Condition\ConditionCheckerInterface;
use CoreShop\Component\Rule\Model\RuleInterface;
use CoreShop\Component\Store\Model\StoreInterface;

final class StoresConditionChecker implements ConditionCheckerInterface
{
    public function isValid(
        ResourceInterface $subject,
        RuleInterface $rule,
        array $configuration,
        array $params = [],
    ): bool {
        if (!array_key_exists('store', $params) || !$params['store'] instanceof StoreInterface) {
            return false;
        }

        return in_array($params['store']->getId(), $configuration['stores']);
    }
}
