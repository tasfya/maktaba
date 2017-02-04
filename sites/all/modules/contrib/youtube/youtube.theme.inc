<?php
/**
 * @file
 * Theme functions for the YouTube field module.
 */

/**
 * Theme function for videos.
 */
function theme_youtube_video($variables) {
  $id = $variables['video_id'];
  $size = $variables['size'];
  $width = array_key_exists('width', $variables)? $variables['width'] : NULL;
  $height = array_key_exists('height', $variables)? $variables['height'] : NULL;
  $autoplay = array_key_exists('autoplay', $variables)? $variables['autoplay'] : FALSE;

  // Get YouTube settings.
  $suggest = variable_get('youtube_suggest', TRUE);
  $privacy = variable_get('youtube_privacy', FALSE);
  $wmode = variable_get('youtube_wmode', TRUE);
  $dimensions = youtube_get_dimensions($size, $width, $height);

  // Protocol changes based on current page TODO.
  $protocol = (isset($_SERVER['HTTPS'])) ? 'https' : 'http';

  // Query string changes based on setings.
  $query = array();
  if (!$suggest) {
    $query['rel'] = '0';
  }
  if ($wmode) {
    $query['wmode'] = 'opaque';
  }
  if ($autoplay) {
    $query['autoplay'] = '1';
  }

  // Domain changes based on settings.
  $domain = ($privacy) ? 'youtube-nocookie.com' : 'youtube.com';

  $path = $protocol . '://www.' . $domain . '/embed/' . $id;
  $src = url($path, array('query' => $query));

  $output = '<iframe width="' . $dimensions['width'] . '" 
    height="' . $dimensions['height'] . '" src="' . $src . '" 
    frameborder="0" allowfullscreen></iframe>';

  return $output;
}


/**
 * Theme function for thumbnails.
 */
function theme_youtube_thumbnail($variables) {
  $id = $variables['video_id'];
  $style = $variables['image_style'];

  // Get YouTube settings - TODO is this needed?
  $size = variable_get('youtube_size', '420x315');
  $dimensions = youtube_get_dimensions($size);

  $files = variable_get('file_public_path', conf_path() . '/files');
  $youtube = variable_get('youtube_thumb_dir', 'youtube');
  $dest = $files . '/' . $youtube . '/' . $id . '.png';

  // Check to see if a thumbnail exists locally.
  if (!file_exists($dest)) {
    // Retrieve the image from YouTube.
    if (!youtube_get_remote_image($id)) {
      // Use the remote source if local copy fails.
      $src = youtube_build_remote_image_path($id);
      return theme('image', array('path' => $src));
    }
  }

  if ($style) {
    $uri = 'public://' . $youtube . '/' . $id . '.png';
    $image = theme('image_style', array('style_name' => $style, 'path' => $uri));
  }
  else {
    $path = $files . '/' . $youtube . '/' . $id . '.png';
    $image = theme('image', array('path' => $path));
  }

  // Check if an url path is provided
  if ($variables['image_link'] != NULL) {
    $url_path = $variables['image_link']['path'];
    $options = $variables['image_link']['options'];
    $image = l($image, $url_path, $options);
  }

  return $image;
}