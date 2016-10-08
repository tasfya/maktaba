<?php
/**
 * @file
 * System module integration.
 */

/**
 * Implements hook_form_alter().
 */
function aloha_form_alter(&$form, &$form_state, $form_id) {
  drupal_alter('aloha_form', $form, $form_state, $form_id);
}

function aloha_form_user_profile_form_alter(&$form, &$form_state) {
  if ($form['#user_category'] == 'account') {
    $account = $form['#user'];
    if (user_access('use aloha', $account)) {
      $form['front_end_editing'] = array(
        '#type' => 'fieldset', 
        '#title' => t('Front end editing'), 
        '#weight' => 4, 
        '#collapsible' => TRUE,
      );

      $form['front_end_editing']['front_end_edit'] = array(
        '#type' => 'checkbox', 
        '#title' =>  t('Enable front end editing'), 
        '#description' => t('Enable possibility to use front end editing globally.'), 
        '#default_value' => isset($account->data['front_end_edit']) ? $account->data['front_end_edit'] : 1,
      );
    }
  }
}

function aloha_user_presave(&$edit, $account, $category) {
  if (isset($edit['front_end_edit'])) {
    $edit['data']['front_end_edit'] = $edit['front_end_edit'];
  }
}

function _aloha_use_fee() {
  global $user;
  if (arg(0) == 'admin') return FALSE;
  return !isset($user->data['front_end_edit']) || $user->data['front_end_edit'];
}
