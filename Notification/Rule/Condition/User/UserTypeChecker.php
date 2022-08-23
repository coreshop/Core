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

namespace CoreShop\Component\Core\Notification\Rule\Condition\User;

use CoreShop\Component\Customer\Model\CustomerInterface;
use CoreShop\Component\Notification\Rule\Condition\AbstractConditionChecker;

class UserTypeChecker extends AbstractConditionChecker
{
    public const TYPE_REGISTER = 'register';

    public const TYPE_PASSWORD_RESET = 'password-reset';

    public const TYPE_NEWSLETTER_DOUBLE_OPT_IN = 'newsletter-double-opt-in';

    public const TYPE_NEWSLETTER_CONFIRMED = 'newsletter-confirmed';

    public function isNotificationRuleValid($subject, array $params, array $configuration): bool
    {
        if ($subject instanceof CustomerInterface) {
            $paramsToExist = [
                'type',
            ];

            foreach ($paramsToExist as $paramToExist) {
                if (!array_key_exists($paramToExist, $params)) {
                    return false;
                }
            }

            $type = $params['type'];

            if ($configuration['userType'] === $type) {
                return true;
            }
        }

        return false;
    }
}
