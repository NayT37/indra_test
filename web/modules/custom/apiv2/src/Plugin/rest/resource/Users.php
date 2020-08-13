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
 *   id = "endpoint",
 *   label = @Translation("Users"),
 *   uri_paths = {
 *     "canonical" = "/apiv2/user"
 *   }
 * )
 */
class Users extends ResourceBase {

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
     *
     * @return \Drupal\rest\ResourceResponse
     *   The HTTP response object.
     *
     * @throws \Symfony\Component\HttpKernel\Exception\HttpException
     *   Throws exception expected.
     */
    public function get() {
        // You must to implement the logic of your REST Resource here.
        // Use current user after pass authentication to validate access.
        if (!$this->currentUser->hasPermission('access content')) {
            throw new AccessDeniedHttpException();
        }

        $nids = \Drupal::entityQuery('node')->condition('type', 'user')->execute();
        if($nids){
          $nodes =  \Drupal\node\Entity\Node::loadMultiple($nids);
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
        }
        
        $response = new ResourceResponse($data, 200);
        // In order to generate fresh result every time (without clearing 
        // the cache), you need to invalidate the cache.
        $response->addCacheableDependency($data);
        return $response;
    }

}
