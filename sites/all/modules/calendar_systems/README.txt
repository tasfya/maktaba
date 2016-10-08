=== DESCRIPTION  ===

  Support for various calendar systems like Jalali, Gregorian, Hijir, Hebew, etc.

=== INSTALLATION ===

  - Install and enable the module as usual: http://drupal.org/node/70151

  - Add the following code right after "$timezones = &$drupal_static_fast['timezones'];" in "/includes/common.inc" file
    or apply the patch located in patches/ in module's directory. if you don't know how to apply patches, there is a
    good guide here: http://drupal.org/patch/apply

  // Calendar Systems module new hook (The actual hook is hook_format_date this one is only a workaround to 
  // prevent incompatibility with modules that already have a function called module_name_format_date)
  foreach (module_implements('format_date_calendar_systems') AS $module) {
    $function = $module .'_format_date';
    $r = $function($timestamp, $type, $format, $timezone, $langcode);
    
    if ($r != FALSE) {
      return $r;
    }
  }

  - Goto "admin/config/regionals/calendar-systems" and configure your profiles.
  
=== API ===

calendar_systems_get_calendar_instance

  You can use calendar_systems_get_calendar_instance($calendar_system = NULL, $language = NULL) to get and instance
  of a calendar system.
  For exmaple to get an instance of iranian calendar system : 
  $calendar = calendar_systems_get_calendar_instance('iranian');
  $calendar->date('Y-m-d',mktime());

calendar_systems_get_calendar_system_name
  calendar_systems_get_calendar_system_name()
  Result : 'default'
  
  calendar_systems_get_calendar_system_name('fa')
  Result : 'iranian'

=== Support ===

Found a bug? report it here http://drupal.org/node/add/project-issue/calendar_systems

AUTHORS AND MAINTAINERS
=======================

  Sina Salek - Original developer. http://sina.salek.ws
  Sepehr Lajevardi - D7 developer.