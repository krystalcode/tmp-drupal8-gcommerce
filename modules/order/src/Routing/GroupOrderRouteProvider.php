<?php

namespace Drupal\gcommerce_order\Routing;

use Drupal\commerce_order\Entity\OrderType;
use Symfony\Component\Routing\Route;

/**
 * Provides routes for `group_commerce_order` group content.
 */
class GroupOrderRouteProvider {

  /**
   * Provides the shared collection route for group order plugins.
   */
  public function getRoutes() {
    $routes = $plugin_ids = $permissions_add = $permissions_create = [];

    foreach (OrderType::loadMultiple() as $name => $order_type) {
      $plugin_id = "group_commerce_order:$name";

      $plugin_ids[] = $plugin_id;
      $permissions_add[] = "create $plugin_id content";
      $permissions_create[] = "create $plugin_id entity";
    }

    // If there are no order types yet, we cannot have any plugin IDs and should
    // therefore exit early because we cannot have any routes for them either.
    if (empty($plugin_ids)) {
      return $routes;
    }

    $routes['entity.group_content.group_commerce_order_relate_page'] = new Route('group/{group}/commerce_order/add');
    $routes['entity.group_content.group_commerce_order_relate_page']
      ->setDefaults([
        '_title' => 'Relate order',
        '_controller' => '\Drupal\gcommerce_order\Controller\GroupOrderController::addPage',
      ])
      ->setRequirement('_group_permission', implode('+', $permissions_add))
      ->setRequirement('_group_installed_content', implode('+', $plugin_ids))
      ->setOption('_group_operation_route', TRUE);

    $routes['entity.group_content.group_commerce_order_add_page'] = new Route('group/{group}/commerce_order/create');
    $routes['entity.group_content.group_commerce_order_add_page']
      ->setDefaults([
        '_title' => 'Create order',
        '_controller' => '\Drupal\gcommerce_order\Controller\GroupOrderController::addPage',
        'create_mode' => TRUE,
      ])
      ->setRequirement('_group_permission', implode('+', $permissions_create))
      ->setRequirement('_group_installed_content', implode('+', $plugin_ids))
      ->setOption('_group_operation_route', TRUE);

    return $routes;
  }

}
