<?php

/**
 * @file
 * Adaptive Image - Adaptive images for Drupal
 * @see http://adaptive-images.com/
 *
 * @author
 * Stefan Auditor <stefan.auditor@erdfisch.de>
 */

/**
 * Copy of image_style_deliver() for use with adaptive images.
 */
function adaptive_image_style_deliver($style, $scheme) {
  $settings = $resolutions = array();
  $settings = adaptive_image_effect_settings($style);

  $mobile_first = (boolean) $settings['mobile_first'];
  $resolutions = explode(',', $settings['resolutions']);

  /* Do we need to switch mobile first off? */
  if (adaptive_image_browser_detect()) {
    $mobile_first = FALSE;
  }

  $resolution = adaptive_image_resolution($resolutions);

  /* No resolution was found (no cookie or invalid cookie) */
  if (!$resolution && count($resolutions)) {
    // We send the lowest resolution for mobile-first approach, and highest otherwise
    $resolution = $mobile_first ? min($resolutions) : max($resolutions);
  }

  // Check that the style is defined and the scheme is valid.
  if (!$style || !file_stream_wrapper_valid_scheme($scheme)) {
    drupal_exit();
  }

  foreach ($style['effects'] as $id => $effect) {
    if ($effect['name'] == 'adaptive_image') {
      $style['effects'][$id]['data']['width'] = $resolution;
      $style['effects'][$id]['data']['height'] = NULL;
      break;
    }
  }

  $args = func_get_args();
  array_shift($args);
  array_shift($args);
  $target = implode('/', $args);

  $image_uri = $scheme . '://' . $target;
  $derivative_uri = image_style_path($style['name'], $image_uri);

  if ($resolution) {
    $path_parts = pathinfo($derivative_uri);
    $derivative_uri = $path_parts['dirname'] . '/' . $resolution . '/' . $path_parts['basename'];
  }

  // If using the private scheme, let other modules provide headers and
  // control access to the file.
  if ($scheme == 'private') {
    if (file_exists($derivative_uri)) {
      file_download($scheme, file_uri_target($derivative_uri));
    }
    else {
      $headers = module_invoke_all('file_download', $image_uri);
      if (in_array(-1, $headers) || empty($headers)) {
        return drupal_access_denied();
      }
      if (count($headers)) {
        foreach ($headers as $name => $value) {
          drupal_add_http_header($name, $value);
        }
      }
    }
  }

  // Don't start generating the image if the derivative already exists or if
  // generation is in progress in another thread.
  $lock_name = 'image_style_deliver:' . $style['name'] . ':' . drupal_hash_base64($image_uri);
  if (!file_exists($derivative_uri)) {
    $lock_acquired = lock_acquire($lock_name);
    if (!$lock_acquired) {
      // Tell client to retry again in 3 seconds. Currently no browsers are known
      // to support Retry-After.
      drupal_add_http_header('Status', '503 Service Unavailable');
      drupal_add_http_header('Retry-After', 3);
      print t('Image generation in progress. Try again shortly.');
      drupal_exit();
    }
  }

  // Try to generate the image, unless another thread just did it while we were
  // acquiring the lock.
  $success = file_exists($derivative_uri) || image_style_create_derivative($style, $image_uri, $derivative_uri);

  if (!empty($lock_acquired)) {
    lock_release($lock_name);
  }

  if ($success) {
    $image = image_load($derivative_uri);
    file_transfer($image->source, array('Content-Type' => $image->info['mime_type'], 'Content-Length' => $image->info['file_size']));
  }
  else {
    watchdog('image', 'Unable to generate the derived image located at %path.', array('%path' => $derivative_uri));
    drupal_add_http_header('Status', '500 Internal Server Error');
    print t('Error generating image.');
    drupal_exit();
  }
}

/**
 * Check for common desktop patterns in the user agent
 */
function adaptive_image_browser_detect() {
  $userAgent = strtolower($_SERVER['HTTP_USER_AGENT']);

  // Identify the OS platform. Match only desktop OSs
  if (strpos($userAgent,'macintosh') ||
      strpos($userAgent,'windows nt') ||
      strpos($userAgent,'x11')) {
    return TRUE;
  }
}
