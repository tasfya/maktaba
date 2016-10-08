The YouTube field module provides a simple field that allows you to add a
YouTube video to a content type, user, or any entity.

Display types include:

 * YouTube videos of various sizes.
 * YouTube thumbnails with image styles.

This module is a lightweight alternative to Media or Embedded Media Field. If
you're looking for a way to add video fields from more than one provider, you
may want to consider looking into either of those two modules.


Installation
------------
Follow the standard contributed module installation process:
http://drupal.org/documentation/install/modules-themes/modules-7


Requirements
------------
All dependencies of this module are enabled by default in Drupal 7.x.


Use
---
To use this module, create a new field of type 'YouTube video'. This field will
accept YouTube URLs of the following formats:

 * youtube.com/watch?v=[video_id]
 * youtu.be/[video_id]

It will not be a problem if users submit values with http:// or https:// and
additional parameters after the URL will be ignored.


Configuration
-------------
In both Views and these field settings, a YouTube field can be output as a
video of either one of four sizes or a custom size, with the ability to
autoplay if necessary. The thumbnail of the YouTube image can also be used and
can link to either the content, the YouTube video itself, or nothing at all.

To configure the field settings:

 1. click 'manage display' on the listing of Content Types (under Structure)
 2. click the configuration gear to the right of the YouTube field


Support
-------
Please use the issue queue for filing bugs with this module at
http://drupal.org/project/issues/youtube

