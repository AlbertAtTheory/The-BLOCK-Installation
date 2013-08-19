<?php

/**
 * @file
 *
 * Settings form and helper functions for touch icons module
 */

/**
 * helper function to get theme key for theme settings page.
 *
 * if we don't receive $key parameter like system_theme_settings()
 * we can get it from path, i.e. 'admin/appearance/settings/THEMENAME'
 *
 * NOTE: this is NOT the active theme being used to display the page; it is the
 * theme currently being configured on the theme settings form.
 */
function _touch_icons_get_theme_settings_key() {
  $key = arg(3);
  if ($key == "global") { // undesirable side-effect of admin_menu.module
    $key = '';
  }
  return $key;
}

/**
 * Implement hook_form_FORM_ID_alter().
 * Implement hook_form_system_theme_settings_alter().
 *
 * Add Apple Touch icon customization settings to the theme settings form
 *
 * @see system_theme_settings()
 */
function touch_icons_form_system_theme_settings_alter(&$form, &$form_state) {

  // we don't receive $key parameter like system_theme_settings()
  $key = _touch_icons_get_theme_settings_key();

  // display toggles
  $form['theme_settings']['toggle_touch_icon_plain'] = array(
    '#type' => 'checkbox',
    '#title' => t('iOS touch icon'),
    '#default_value' => theme_get_setting('toggle_touch_icon_plain', $key),
  );
  $form['theme_settings']['toggle_touch_icon_precomp'] = array(
    '#type' => 'checkbox',
    '#title' => t('iOS touch icon (precomposed)'),
    '#default_value' => theme_get_setting('toggle_touch_icon_precomp', $key),
  );
  $form['theme_settings']['toggle_startup_image'] = array(
    '#type' => 'checkbox',
    '#title' => t('iOS startup image'),
    '#default_value' => theme_get_setting('toggle_startup_image', $key),
  );
  
  // Grab the array of possible devices and modes.
  $config = _touch_icons_icon_configuration();

  // Touch icon settings fieldset.
  $form['touch_icons'] = array(
    '#collapsible' => TRUE,
    '#type'        => 'fieldset',
    '#title'       => t('Touch icon settings'),
    '#description' => t('Touch icons are shown as bookmarks on the home-screen of an iOS device.'),
  );

  // Now we loop through the configuration and build our form.
  foreach ($config as $platform => $settings) {
    $form['touch_icons']['default_touch_icons_' . strtolower($platform)] = array(
      '#type' => 'checkbox',
      '#title' => t('Use the default @platform touch icons.', array('@platform' => $platform)),
      '#default_value' => theme_get_setting('default_touch_icons_' . strtolower($platform), $key),
      '#tree' => FALSE,
      '#description' => t('Check here if you want the theme to use the @platform touch icons supplied with it.', array('@platform' => $platform)),
    );
    
    foreach ($settings as $mode => $icon) {
      // Check to make sure we're using only Icons, not Startup Images.
      if ($mode == 'i' || $mode == 'ic') {
        $touch_icon_path = theme_get_setting($icon['normal']['id'], $key);
        $touch_icon_path_retina = theme_get_setting($icon['retina']['id'], $key);

        // If $touch_icon_path_plain is a public:// URI, display the path relative
        // to the files directory; stream wrappers are not end-user friendly.
        if (file_uri_scheme($touch_icon_path) == 'public') {
          $touch_icon_path = file_uri_target($touch_icon_path);
        }
        if (file_uri_scheme($touch_icon_path_retina) == 'public') {
          $touch_icon_path_retina = file_uri_target($touch_icon_path_retina);
        }

        $form['touch_icons'][strtolower($platform)][$mode] = array(
          '#type' => 'fieldset',
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
          '#title' => $icon['title'],
          '#description' => $icon['description'],
          '#states' => array(
            // Hide the icon settings when using the default icon.
            'invisible' => array(
              'input[name="default_touch_icons_' . strtolower($platform) . '"]' => array('checked' => TRUE),
            ),
          ),
          '#element_validate' => array('_touch_icons_validate'),
        );

        // "Normal" resolution Touch icons.
        $form['touch_icons'][strtolower($platform)][$mode][$icon['normal']['id']] = array(
          '#type' => 'textfield',
          '#title' => t('Path to custom icon'),
          '#default_value' => $touch_icon_path,
          '#description' => t('The path to the file you would like to use as your touch icon instead of the default touch icon.'),
        );
        $form['touch_icons'][strtolower($platform)][$mode][$icon['normal']['id'] . '_upload'] = array(
          '#type' => 'file',
          '#title' => t('Upload icon image'),
          '#description' => t("If you don't have direct file access to the server, use this field to upload your touch icon."),
        );

        // Checkbox to toggle the display of the Retina setting.
        // @todo This should clear the current settings if unchecked.
        $form['touch_icons'][strtolower($platform)][$mode]['define_retina_icons_' . $mode . '_' . strtolower($platform)] = array(
          '#type' => 'checkbox',
          '#title' => t('Assign Retina-resolution icons.'),
          '#default_value' => theme_get_setting('define_retina_icons_' . strtolower($platform), $key),
          '#tree' => FALSE,
          '#description' => t('Retina dimensions are @width x @height', array('@width' => $icon['retina']['width'], '@height' => $icon['retina']['height'])),
        );

        // "Retina" resolution Touch icons.
        $form['touch_icons'][strtolower($platform)][$mode][$icon['retina']['id']] = array(
          '#type' => 'textfield',
          '#title' => t('Path to custom icon'),
          '#default_value' => $touch_icon_path_retina,
          '#description' => t('The path to the file you would like to use as your touch icon instead of the default touch icon.'),
          '#states' => array(
            // Hide the settings if the Retina checkbox isn't checked.
            'invisible' => array(
              'input[name="define_retina_icons_' . $mode . '_' . strtolower($platform) . '"]' => array('checked' => FALSE),
            ),
          ),
        );
        $form['touch_icons'][strtolower($platform)][$mode][$icon['retina']['id'] . '_upload'] = array(
          '#type' => 'file',
          '#title' => t('Upload icon image'),
          '#description' => t("If you don't have direct file access to the server, use this field to upload your touch icon."),
          '#states' => array(
            // Hide the settings if the Retina checkbox isn't checked.
            'invisible' => array(
              'input[name="define_retina_icons_' . $mode . '_' . strtolower($platform) . '"]' => array('checked' => FALSE),
            ),
          ),
        );
      }
    }
  }
  
  // Startup image settings fieldset.
  $form['startup_image'] = array(
    '#attributes' => array('class' => array('theme-settings-bottom')),
    '#type'        => 'fieldset',
    '#title'       => t('Startup image settings'),
    '#description' => t('Startup images are shown on web-app enabled sites on iOS devices.'),
  );
  
  // Now we loop through the configuration and build our form.
  foreach ($config as $platform => $settings) {
    $form['startup_image']['default_startup_image_' . strtolower($platform)] = array(
      '#type' => 'checkbox',
      '#title' => t('Use the default @platform startup image.', array('@platform' => $platform)),
      '#default_value' => theme_get_setting('default_startup_image_' . strtolower($platform), $key),
      '#tree' => FALSE,
      '#description' => t('Check here if you want the theme to use the @platform startup image supplied with it.', array('@platform' => $platform)),
    );

    foreach ($settings as $mode => $startup_image) {
      // Check to make sure we're using only Startup Images, not icons.
      if ($mode == 'p' || $mode == 'l') {
        $startup_image_path = theme_get_setting($startup_image['normal']['id'], $key);
        $startup_image_path_retina = theme_get_setting($startup_image['retina']['id'], $key);

        // If $touch_icon_path_plain is a public:// URI, display the path relative
        // to the files directory; stream wrappers are not end-user friendly.
        if (file_uri_scheme($startup_image_path) == 'public') {
          $startup_image_path = file_uri_target($startup_image_path);
        }
        if (file_uri_scheme($startup_image_path_retina) == 'public') {
          $startup_image_path_retina = file_uri_target($startup_image_path_retina);
        }

        $form['startup_image'][strtolower($platform)][$mode] = array(
          '#type' => 'fieldset',
          '#collapsible' => TRUE,
          '#collapsed' => TRUE,
          '#title' => $startup_image['title'],
          '#description' => $startup_image['description'],
          '#states' => array(
            // Hide the icon settings when using the default icon.
            'invisible' => array(
              'input[name="default_startup_image_' . strtolower($platform) . '"]' => array('checked' => TRUE),
            ),
          ),
          '#element_validate' => array('_touch_icons_validate'),
        );
        
        // "Normal" resolution Touch icons.
        $form['startup_image'][strtolower($platform)][$mode][$startup_image['normal']['id']] = array(
          '#type' => 'textfield',
          '#title' => t('Path to custom startup image'),
          '#default_value' => $startup_image_path,
          '#description' => t('The path to the file you would like to use as your startup image instead of the default startup image.'),
        );
        $form['startup_image'][strtolower($platform)][$mode][$startup_image['normal']['id'] . '_upload'] = array(
          '#type' => 'file',
          '#title' => t('Upload startup image'),
          '#description' => t("If you don't have direct file access to the server, use this field to upload your startup image."),
        );

        // Checkbox to toggle the display of the Retina setting.
        // @todo This should clear the current settings if unchecked.
        $form['startup_image'][strtolower($platform)][$mode]['define_retina_startup_' . $mode . '_' . strtolower($platform)] = array(
          '#type' => 'checkbox',
          '#title' => t('Assign Retina-resolution startup image.'),
          '#default_value' => theme_get_setting('define_retina_startup_' . strtolower($platform), $key),
          '#tree' => FALSE,
          '#description' => t('Retina dimensions are @width x @height', array('@width' => $startup_image['retina']['width'], '@height' => $startup_image['retina']['height'])),
        );

        // "Retina" resolution Touch icons.
        $form['startup_image'][strtolower($platform)][$mode][$startup_image['retina']['id']] = array(
          '#type' => 'textfield',
          '#title' => t('Path to custom startup image'),
          '#default_value' => $startup_image_path_retina,
          '#description' => t('The path to the file you would like to use as your startup image instead of the default startup image.'),
          '#states' => array(
            // Hide the settings if the Retina checkbox isn't checked.
            'invisible' => array(
              'input[name="define_retina_startup_' . $mode . '_' . strtolower($platform) . '"]' => array('checked' => FALSE),
            ),
          ),
        );
        $form['startup_image'][strtolower($platform)][$mode][$startup_image['retina']['id'] . '_upload'] = array(
          '#type' => 'file',
          '#title' => t('Upload startup image'),
          '#description' => t("If you don't have direct file access to the server, use this field to upload your startup image."),
          '#states' => array(
            // Hide the settings if the Retina checkbox isn't checked.
            'invisible' => array(
              'input[name="define_retina_startup_' . $mode . '_' . strtolower($platform) . '"]' => array('checked' => FALSE),
            ),
          ),
        );
      }
    }
  }
}

/**
 * Validation
 */
function _touch_icons_validate($element, &$form_state) {
  return;
}

/**
 * Validate custom plain touch icon settings
 */
function _touch_icons_plain_validate($element, &$form_state) {
  // If the user provided a path for plain touch icon
  // make sure a file exists at that path.
  if ($form_state['values']['touch_icon_path_plain']) {
    $path = _system_theme_settings_validate_path($form_state['values']['touch_icon_path_plain']);
    if (!$path) {
      form_set_error('touch_icon_path_plain', t('The custom plain-touch-icon path is invalid.'));
      return;
    }
  }

  // Handle file uploads.
  $validators = array('file_validate_is_image' => array());

  // Check for a new uploaded plain touch icon.
  $file = file_save_upload('touch_icon_upload_plain', $validators);
  if (isset($file)) {
    // File upload was attempted.
    if ($file) {
      // If the user uploaded a new plain touch-icon, save it to a permanent
      // location and use it in place of the default theme-provided file.
      $filename = file_unmanaged_copy($file->uri);
      $form_state['values']['touch_icon_path_plain'] = $filename;
      $form_state['values']['default_touch_icon_plain'] = 0;
      $form_state['values']['toggle_touch_icon_plain'] = 1;
      unset($form_state['values']['touch_icon_upload_plain']);
    }
    else {
      // File upload failed.
      form_set_error('touch_icon_upload_plain', t('The touch icon (plain) could not be uploaded.'));
      return;
    }
  }

  // If the user entered a path relative to the system files directory for a
  // plain touch icon, store a public:// URI so the theme system can handle it.
  if (!empty($form_state['values']['touch_icon_path_plain'])) {
    $form_state['values']['touch_icon_path_plain'] = _system_theme_settings_validate_path($form_state['values']['touch_icon_path_plain']);
  }
}

/**
 * Validate custom precomposed touch icon settings
 */
function _touch_icons_precomp_validate($element, &$form_state) {
  // If the user provided a path for precomposed touch icon
  // make sure a file exists at that path.
  if ($form_state['values']['touch_icon_path_precomp']) {
    $path = _system_theme_settings_validate_path($form_state['values']['touch_icon_path_precomp']);
    if (!$path) {
      form_set_error('touch_icon_path_precomp', t('The custom precomp-touch-icon path is invalid.'));
      return;
    }
  }

  // Handle file uploads.
  $validators = array('file_validate_is_image' => array());

  // Check for a new uploaded precomposed touch icon.
  $file = file_save_upload('touch_icon_upload_precomp', $validators);
  if (isset($file)) {
    // File upload was attempted.
    if ($file) {
      // If the user uploaded a new plain touch-icon, save it to a permanent
      // location and use it in place of the default theme-provided file.
      $filename = file_unmanaged_copy($file->uri);
      $form_state['values']['touch_icon_path_precomp'] = $filename;
      $form_state['values']['default_touch_icon_precomp'] = 0;
      $form_state['values']['toggle_touch_icon_precomp'] = 1;
      unset($form_state['values']['touch_icon_upload_precomp']);
    }
    else {
      // File upload failed.
      form_set_error('touch_icon_upload_precomp', t('The touch icon (precomposed) could not be uploaded.'));
      return;
    }
  }

  // If the user entered a path relative to the system files directory for
  // a precomposed touch icon, store a public:// URI so the theme system can
  // handle it.
  if (!empty($form_state['values']['touch_icon_path_precomp'])) {
    $form_state['values']['touch_icon_path_precomp'] = _system_theme_settings_validate_path($form_state['values']['touch_icon_path_precomp']);
  }
}

