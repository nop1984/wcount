<?php

/**
 * draft for calculated field. Not used anywhere
 */

namespace Drupal\wcount;

use Drupal\Core\Field\FieldItemList;
use Drupal\Core\TypedData\ComputedItemListTrait;
use Drupal\Core\Field\FieldItemListInterface;

class CounterSQL extends FieldItemList implements FieldItemListInterface
{

    use ComputedItemListTrait;

    protected function computeValue()
    {
        $some_calculated_values = [111, 2222, 3333];
        foreach ($some_calculated_values as $delta => $value) {
            $this->list[$delta] = $this->createItem($delta, $value);
        }
    }
}
