<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2021 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

declare(strict_types=1);

namespace CoreShop\Component\Core\Product\Rule\Condition;

use CoreShop\Component\Address\Model\CountryInterface;
use CoreShop\Component\Address\Model\ZoneInterface;
use CoreShop\Component\Resource\Model\ResourceInterface;
use CoreShop\Component\Rule\Condition\ConditionCheckerInterface;
use CoreShop\Component\Rule\Model\RuleInterface;

final class ZonesConditionChecker implements ConditionCheckerInterface
{
    public function isValid(
        ResourceInterface $subject,
        RuleInterface $rule,
        array $configuration,
        array $params = []
    ): bool {
        if (!array_key_exists('country', $params) || !$params['country'] instanceof CountryInterface) {
            return false;
        }

        $country = $params['country'];

        if (!$country->getZone() instanceof ZoneInterface) {
            return false;
        }

        return in_array($country->getZone()->getId(), $configuration['zones']);
    }
}
