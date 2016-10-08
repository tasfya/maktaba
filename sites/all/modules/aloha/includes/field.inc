<?php
/**
 * @file
 * Field module integration.
 */

/**
 * Implements hook_aloha_type_info() on behalf of field.module.
 */
function field_aloha_type_info() {
  return array(
    'field' => array(
      'save callback' => 'aloha_save_field',
      'access callback' => 'aloha_access_field',
    ),
  );
}

/**
 * Implements hook_aloha_form_alter() on behalf of field.module.
 */
function field_aloha_form_alter(&$form, &$form_state, $form_id) {
  if ($form_id == 'aloha_admin') {
    $form['aloha_fields'] = array(
      '#type' => 'checkbox',
      '#title' => t('Enable front end editing for fields.'),
      '#description' => t('Enables front end editing globally for all fields.'),
      '#default_value' => variable_get('aloha_fields', TRUE),
    );
  }

  // Add setting to enable/disable front end editing per field basis.
  if ($form_id === 'field_ui_field_edit_form' && isset($form['#field']['type'])) {
    if ($form['#field']['type'] === 'text' || $form['#field']['type'] === 'text_long' || $form['#field']['type'] === 'text_with_summary') {
      $form['field']['settings']['front_end_editable'] = array(
        '#type' => 'checkbox',
        '#title' => t('Enable front end editing'),
        '#description' => t('Enables front end editing for this field. This does not have effect if front end editing is enabled globally.'),
        '#default_value' =>  isset($form['#field']['settings']['front_end_editable']) ? $form['#field']['settings']['front_end_editable'] : '0',
      );
    }
  }
}

/**
 * Implements hook_field_attach_view_alter().
 */
function aloha_field_attach_view_alter(&$output, $context) {
  // Only attach aloha to node entities
  if ($context['entity_type'] != 'node') {
    return;
  }

  $front_end_editing_globally = variable_get('aloha_fields', TRUE);
  foreach (element_children($output) as $field_name) {
    $field = field_info_field($field_name);
    // Check if front end editing is enabled globally or per field basis.
    if (($front_end_editing_globally
        || (isset($field['settings']['front_end_editable']) && $field['settings']['front_end_editable'])
        && _aloha_use_fee())) {
      $instance = $output[$field_name];
      if (aloha_access_field($field_name, $instance['#object'])) {
        // Attach required files.
        _aloha_include_files();

        // Process each field.
        foreach ($instance['#items'] as $delta => $item) {
          switch ($instance['#field_type']) {
            case 'text':
            case 'text_with_summary':
            case 'text_long':
            case 'number_integer':
            case 'number_float':
            case 'number_decimal':
              $key = "{$field_name}-{$instance['#object']->vid}-{$delta}";

              $data = array(
                'type' => 'field',
                'nid' => $instance['#object']->nid,
                'revision_id' => $instance['#object']->vid,
                'field_name' => $field_name,
                'delta' => $delta,
                'entity_type' => $instance['#entity_type'],
                'field_type' => $instance['#field_type'],
              );

              // We need to render this element in the case that it isn't a
              // standard #markup element.
              $output[$field_name][$delta] = array('#markup' => render($output[$field_name][$delta]));

              _aloha_add_target_to_region($output[$field_name][$delta]['#markup'], $key);
              _aloha_add_region_to_js($key, $data);
              break;
          }
        }
      }
    }
  }
}

/**
 * Save callback for field.module.
 */
function aloha_save_field() {
  if (isset($_POST['html'])) {
    $node = node_load(check_plain($_POST['nid']));
    $lang = field_language('node', $node, $_POST['field_name']);
    switch ($_POST['field_type']) {
      case 'text':
      case 'text_with_summary':
      case 'text_long':
      case 'number_integer':
      case 'number_float':
      case 'number_decimal':
        $node->{$_POST['field_name']}[$lang][$_POST['delta']]['value'] = $_POST['html'];
        break;
    }
    try {
      node_save($node);
      return drupal_json_output(array(
        'title' => 'Field',
        'type' => 'field',
        'status' => 'saved',
        'delta' => $_POST['delta'],
      ));
    } catch(Exception $e) {
      return drupal_json_output(array(
        'title' => 'Field',
        'type' => 'field',
        'status' => 'error',
        'delta' => $_POST['delta'],
      ));
    }
  }
}

/**
 * Access callback for field.module.
 */
function aloha_access_field($field_name = NULL, $node = NULL) {
  $field_name = is_null($field_name) ? check_plain($_POST['field_name']) : $field_name;
  // @TODO: Change this to entity_load() to make this entity type agnostic.
  $node = is_null($node) ? node_load(check_plain($_POST['nid'])) : $node;

  return field_access('edit', $field_name, 'node', $node) && node_access('update', $node) && user_access('use aloha');
}
