<?php

namespace Drupal\apiv2\Plugin\rest\resource;

use Drupal\rest\ModifiedResourceResponse;
use Drupal\rest\Plugin\ResourceBase;
use Drupal\rest\ResourceResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Provides a resource to get view modes by entity and bundle.
 *
 * @RestResource(
 *   id = "user",
 *   label = @Translation("User"),
 *   uri_paths = {
 *     "canonical" = "/api/v2/user/{id}"
 *   }
 * )
 */
class User extends ResourceBase {

  /**
   * A current user instance.
   *
   * @var \Drupal\Core\Session\AccountProxyInterface
   */
  protected $currentUser;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    $instance = parent::create($container, $configuration, $plugin_id, $plugin_definition);
    $instance->logger = $container->get('logger.factory')->get('apiv2');
    $instance->currentUser = $container->get('current_user');
    return $instance;
  }

    /**
     * Responds to GET requests.
     *
     * @param string $payload
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function get($id) {
        // You must to implement the logic of your REST Resource here.
        // Use current user after pass authentication to validate access.
        if (!$this->currentUser->hasPermission('access content')) {
          throw new AccessDeniedHttpException();
      }

      $nid = \Drupal::entityQuery('node')
      ->condition('type', 'user')
      ->condition('field_id_doc', $id)
      ->execute();
      if($nid){
        $nodes =  \Drupal\node\Entity\Node::loadMultiple($nid);
        foreach ($nodes as $key => $value) {
          $data[] = [
            'id' => $value->id(),
            'name' => $value->field_name->getValue()[0]['value'],
            'last_name' => $value->field_last_name->getValue()[0]['value'],
            'email' => $value->field_email->getValue()[0]['value'],
            'birthdate' => $value->field_birthdate->getValue()[0]['value'],
            'id_document' => $value->field_id_doc->getValue()[0]['value'],
            'phone' => $value->field_phone->getValue()[0]['value']
          ];
        }
          $response = new ResourceResponse($data, 200);
          // In order to generate fresh result every time (without clearing 
          // the cache), you need to invalidate the cache.
          $response->addCacheableDependency($data);
          return $response;
      } else {
        $response = new ResourceResponse('User ' . $id . ' not found', 401);
        return $response;
      }
    }
}
