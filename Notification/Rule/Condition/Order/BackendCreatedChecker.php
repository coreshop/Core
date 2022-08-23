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

namespace CoreShop\Component\Core\Notification\Rule\Condition\Order;

use CoreShop\Component\Core\Model\OrderInterface;
use CoreShop\Component\Notification\Rule\Condition\AbstractConditionChecker;
use Webmozart\Assert\Assert;

class BackendCreatedChecker extends AbstractConditionChecker
{
    public function isNotificationRuleValid($subject, array $params, array $configuration): bool
    {
        /**
         * @var OrderInterface $subject
         */
        Assert::isInstanceOf($subject, OrderInterface::class);

        if ($configuration['backendCreated']) {
            return $subject->getBackendCreated();
        }

        return !$subject->getBackendCreated();
    }
}
