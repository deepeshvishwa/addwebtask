<?php

namespace Drupal\addwebtask\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Returns responses for Add Web Solution Task routes.
 */
class AddwebtaskController extends ControllerBase {

  /**
   * Addwebtask json.
   *
   * @param string $key
   *   Site api key.
   * @param int $nid
   *   Page type node id.
   *
   * @return jsonoutput
   *   Json response for page node.
   */
  public function addwebtask_page_json($key, $nid) {

    // Get the Site API Key variable.
    $site_api_key = \Drupal::config('addwebtask.site')->get('siteapikey');

    // Check and Validate site API Key.
    if ($site_api_key === $key) {
      // Check nid is valid.
      if (is_numeric($nid) && $nid > 0) {
        // Load node using the nid.
        $node = \Drupal::entityTypeManager()->getStorage('node')->load($nid);

        // Check if node type is 'page'.
        if (!empty($node) && $node->getType() === 'page') {
          // Build appropriate JSON response.
          $json_response = [
            'nid' => $nid,
            'type' => $node->getType(),
            'title' => $node->getTitle(),
            'body' => $node->get('body')->getValue(),
          ];

          // Respond with the json representation of node.
          return new JsonResponse($json_response);
        }
        else {
          // If node is not page content type.
          return new JsonResponse(["error" => "access denied"], 401, ['Content-Type' => 'application/json']);
        }
      }
    }
    else {
      // If invalid site api key found.
      return new JsonResponse(["error" => "access denied"], 401, ['Content-Type' => 'application/json']);
    }
  }

}
