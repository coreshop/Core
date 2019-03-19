<?php
/**
 * CoreShop.
 *
 * This source file is subject to the GNU General Public License version 3 (GPLv3)
 * For the full copyright and license information, please view the LICENSE.md and gpl-3.0.txt
 * files that are distributed with this source code.
 *
 * @copyright  Copyright (c) 2015-2019 Dominik Pfaffenbauer (https://www.pfaffenbauer.at)
 * @license    https://www.coreshop.org/license     GNU General Public License version 3 (GPLv3)
 */

namespace CoreShop\Component\Core\Product\Calculator;

use CoreShop\Component\Core\Model\ProductUnitDefinitionPriceInterface;
use CoreShop\Component\Product\Calculator\ProductRetailPriceCalculatorInterface;
use CoreShop\Component\Product\Exception\NoRetailPriceFoundException;
use CoreShop\Component\Product\Model\ProductInterface;
use CoreShop\Component\Product\Model\ProductUnitDefinitionInterface;
use CoreShop\Component\Store\Model\StoreInterface;
use Webmozart\Assert\Assert;

final class StoreProductUnitDefinitionPriceCalculator implements ProductRetailPriceCalculatorInterface
{
    /**
     * {@inheritdoc}
     */
    public function getRetailPrice(ProductInterface $subject, array $context)
    {
        /**
         * @var $subject \CoreShop\Component\Core\Model\ProductInterface
         */
        Assert::isInstanceOf($subject, \CoreShop\Component\Core\Model\ProductInterface::class);
        Assert::keyExists($context, 'store');
        Assert::isInstanceOf($context['store'], StoreInterface::class);

        $price = null;

        if (!isset($context['unitDefinition']) || !$context['unitDefinition'] instanceof ProductUnitDefinitionInterface) {
            throw new NoRetailPriceFoundException(__CLASS__);
        }

        $unitDefinitionPrices = $subject->getStoreValues($context['store'])->getProductUnitDefinitionPrices();

        if ($unitDefinitionPrices->count() === 0) {
            throw new NoRetailPriceFoundException(__CLASS__);
        }

        foreach ($subject->getUnitDefinitions()->getAdditionalUnitDefinitions() as $unitDefinition) {

            $filteredDefinitionPrices = $unitDefinitionPrices->filter(function (ProductUnitDefinitionPriceInterface $unitDefinitionPrice) use ($unitDefinition) {
                return $unitDefinition->getId() === $unitDefinitionPrice->getUnitDefinition()->getId();
            });

            if ($filteredDefinitionPrices->count() === 0) {
                throw new NoRetailPriceFoundException(__CLASS__);
            }

            $price = $filteredDefinitionPrices->first()->getPrice();
        }

        if (is_null($price)) {
            return false;
        }

        return $price;
    }
}
