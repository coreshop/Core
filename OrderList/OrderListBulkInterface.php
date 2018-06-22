<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2017 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Component\Core\OrderList;

use Pimcore\Model\DataObject;

interface OrderListBulkInterface
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @param array $processIds
     * @return DataObject\Listing
     */
    public function apply(array $processIds);
}