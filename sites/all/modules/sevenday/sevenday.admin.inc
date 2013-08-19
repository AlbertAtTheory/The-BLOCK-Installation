<?php

/**
 * @file
 * Admin page form callback and validation.
 */

function sevenday_generate_form($form, &$form_state) {
  // User Actions observations.
  $form['observations_user'] = array(
    '#type' => 'textfield',
    '#title' => t('User Actions'),
    '#description' => t('Observations on the User Actions section.'),
    '#default_value' => variable_get('sevenday_observations_user'),
    '#suffix' => $description,
  );
  
  // Visitor Overview observations.
  $form['observations_visitor'] = array(
    '#type' => 'textfield',
    '#title' => t('Visitor Overview'),
    '#description' => t('Observations on the Visitor Overview section.'),
    '#default_value' => variable_get('sevenday_observations_visitor'),
    '#suffix' => $description,
  );
  
  // Social Overview observations.
  $form['observations_social'] = array(
    '#type' => 'textfield',
    '#title' => t('Social Overview'),
    '#description' => t('Observations on the Social Overview section.'),
    '#default_value' => variable_get('sevenday_observations_social'),
    '#suffix' => $description,
  );
  
  // Observations on This Period section.
  $form['observations_period1'] = array(
    '#type' => 'textfield',
    '#title' => t('Observations on This Period'),
    '#default_value' => variable_get('sevenday_observations_period1'),
  );
  $form['observations_period2'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('sevenday_observations_period2'),
  );
  $form['observations_period3'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('sevenday_observations_period3'),
  );
  $form['observations_period4'] = array(
    '#type' => 'textfield',
    '#default_value' => variable_get('sevenday_observations_period4'),
  );

  // Save button
  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Generate Report'),
  );

  return $form;
}

/**
 * Submit handler for xboxvote_api_form().
 */
function sevenday_generate_form_submit($form, &$form_state) {
  variable_set('sevenday_observations_user', check_plain($form_state['values']['observations_user']));
  variable_set('sevenday_observations_visitor', check_plain($form_state['values']['observations_visitor']));
  variable_set('sevenday_observations_social', check_plain($form_state['values']['observations_social']));

  variable_set('sevenday_observations_period1', check_plain($form_state['values']['observations_period1']));
  variable_set('sevenday_observations_period2', check_plain($form_state['values']['observations_period2']));
  variable_set('sevenday_observations_period3', check_plain($form_state['values']['observations_period3']));
  variable_set('sevenday_observations_period4', check_plain($form_state['values']['observations_period4']));

  // Redirect the user to the Seven Day report PDF.
  drupal_goto('admin/reports/sevenday.pdf');
}
