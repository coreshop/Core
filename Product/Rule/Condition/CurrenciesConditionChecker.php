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

namespace CoreShop\Component\Core\Product\Rule\Condition;

use CoreShop\Component\Currency\Model\CurrencyInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Condition\ConditionCheckerInterface;
use CoreShop\Component\Rule\Model\RuleInterface;

final class CurrenciesConditionChecker implements ConditionCheckerInterface
{
    public function isValid(
        ResourceInterface $subject,
        RuleInterface $rule,
        array $configuration,
        array $params = []
    ): bool {
        if (!array_key_exists('currency', $params) || !$params['currency'] instanceof CurrencyInterface) {
            return false;
        }

        return in_array($params['currency']->getId(), $configuration['currencies']);
    }
}
