<?php

/**
 * @file
 * YouTube API admin functions.
 */


/**
 * Implements _youtube_api_form().
 */
function _youtube_api_form($form, &$form_state) {
  $form['developer_key'] = array(
    '#type' => 'textarea',
    '#rows' => 2,
    '#title' => t('Developer API key'),
    '#default_value' => variable_get('youtube_api_developer_key'),
    '#required' => FALSE,
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save'),
  );

  return $form;
}

/**
 * Implements _youtube_api_form_submit().
 */
function _youtube_api_form_submit($form, &$form_state) {
  if (check_plain($form_state['values']['developer_key'])) {
    variable_set('youtube_api_developer_key', check_plain($form_state['values']['developer_key']));

    drupal_set_message(t('Your configuration has been saved.'));
  }
}

function youtube_api_developer_key_page() {
  return drupal_get_form('_youtube_api_form');
}
