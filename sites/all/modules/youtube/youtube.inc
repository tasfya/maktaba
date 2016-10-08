<?php

/**
 * @ file
 * YouTube field helper functions.
 */

/**
 * Returns a list of standard YouTube video sizes.
 */
function youtube_size_options() {
  return array(
    '420x315' => '450px by 315px',
    '480x360' => '480px by 360px',
    '640x480' => '640px by 480px',
    '960x720' => '960px by 720px',
    'custom' => 'custom',
  );
}

/**
 * Splits height and width when given size, as from youtube_size_options.
 */
function youtube_get_dimensions($size = NULL, $width = NULL, $height = NULL) {
  $dimensions = array();
  if ($size == 'custom') {
    $dimensions['width'] = (int) $width;
    $dimensions['height'] = (int) $height;
  }
  else {
    // Locate the 'x'.
    $strpos = strpos($size, 'x');
    // Width is the first dimension.
    $dimensions['width'] = substr($size, 0, $strpos);
    // Height is the second dimension.
    $dimensions['height'] = substr($size, $strpos + 1, strlen($size));
  }

  return $dimensions;
}

/**
 * Retreve youtube thumbnail image via YouTube API.
 *
 * TODO add error messaging if something goes wrong, and return FALSE.
 */
function youtube_get_remote_image($id = NULL) {
  $path = 'http://gdata.youtube.com/feeds/api/videos/' . $id;
  $query = array(
    'v' => '2', 
    'alt' => 'jsonc'
  );
  $url = url($path, array('query' => $query));
  $result = drupal_http_request($url);
  $data = json_decode($result->data);
  // Get the high quality default thumbnail.
  $src = $data->data->thumbnail->hqDefault;

  // Make the actual request to download the file.
  $image_result = drupal_http_request($src);

  // Assure the youtube thumbnail directory exists. 
  $files = variable_get('file_public_path', conf_path() . '/files');
  $youtube_dir = variable_get('youtube_thumb_dir', 'youtube');
  $youtube_path = $files . '/' . $youtube_dir;
  if (!file_prepare_directory($youtube_path, FILE_CREATE_DIRECTORY) && !mkdir($youtube_path, 0775, TRUE)) {
    watchdog('youtube', 'Failed to create YouTube thumbnail directory: %dir', array('%dir' => $youtube_path), WATCHDOG_ERROR);
  }

  // Save the file.
  $dest = $files . '/' . $youtube_dir . '/' . $id . '.png';
  file_put_contents($dest, $image_result->data);

  return TRUE;
}

/**
 * Get images by building correctly formed URL - kinda hackey.
 */
function youtube_build_remote_image_path($id = NULL) {
  if (!$id) {
    return;
  }
  // Default image thumbnail.
  $default = 'http://img.youtube.com/vi/' . $id . '/default.jpg';

  // Full size image.
  $full_size = 'http://img.youtube.com/vi/' . $id . '/0.jpg';
  // High Quality version of the default thumbnail.
  $hq_default = 'http://img.youtube.com/vi/' . $id . '/hqdefault.jpg';
  // High Resolution version of the default thumbnail.
  $max_res_default = 'http://img.youtube.com/vi/' . $id . '/maxresdefault.jpg'; // May not work.
  // First thumbnail.
  $thumb1 = 'http://img.youtube.com/vi/' . $id . '/1.jpg';
  // Second thumbnail.
  $thumb2 = 'http://img.youtube.com/vi/' . $id . '/2.jpg';
  // Third thumbnail.
  $thumb3 = 'http://img.youtube.com/vi/' . $id . '/3.jpg';
  
  return url($default);
}
