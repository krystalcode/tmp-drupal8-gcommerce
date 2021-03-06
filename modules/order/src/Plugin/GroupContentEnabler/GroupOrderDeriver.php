<?php

namespace Drupal\gcommerce_order\Plugin\GroupContentEnabler;

use Drupal\commerce_order\Entity\OrderType;
use Drupal\Component\Plugin\Derivative\DeriverBase;

class GroupOrderDeriver extends DeriverBase {

  /**
   * {@inheritdoc}.
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    foreach (OrderType::loadMultiple() as $name => $order_type) {
      $label = $order_type->label();

      $this->derivatives[$name] = [
        'entity_bundle' => $name,
        'label' => t('Group order (@type)', ['@type' => $label]),
        'description' => t('Adds %type orders to groups both publicly and privately.', ['%type' => $label]),
      ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
