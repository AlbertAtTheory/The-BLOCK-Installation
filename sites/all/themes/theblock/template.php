<?php
function theblock_preprocess_block(&$variables) {
    $variables['user_profile'] = menu_get_object('user');
}

function theblock_preprocess_page(&$variables) {
	$variables['attribution'] = "<small id=\"attribution\"><a href=\"http://ilovetheory.com\">Theory Communication & Design</a></small>";
	
	$alias = drupal_get_path_alias($_GET['q']);
	if ($alias == 'messages') $alias = 'privatemessages';
	$variables['extra_classes'] = explode('/', $alias);
	
	if ($alias != $_GET['q']) {
    	$template_filename = 'page';
		//Break it down for each piece of the alias path
		foreach (explode('/', $alias) as $path_part) {
			$template_filename = $template_filename . '__' . $path_part;
			$variables['theme_hook_suggestions'][] = $template_filename;
			
			$variables['extra_classes'][] = $path_part;
		}
	}
}

function theblock_preprocess_node(&$variables) {
	if ($blocks = block_get_blocks_by_region('content_aside')) {
		$variables['content_aside'] = $blocks;
		$variables['content_aside'] = $blocks;
		$variables['content_aside']['#theme_wrappers'] = array('region');
		$variables['content_aside']['#region'] = 'content_aside';
	}
	if ($blocks = block_get_blocks_by_region('article_side')) {
		$variables['article_side'] = $blocks;
		$variables['article_side'] = $blocks;
		$variables['article_side']['#theme_wrappers'] = array('region');
		$variables['article_side']['#region'] = 'article_side';
	}
}

function theblock_preprocess_image(&$variables) {
	// If the image URL starts with a protocol remove it and use a
	// relative protocol.
	$scheme = file_uri_scheme($variables['path']);
	$protocols = array('http', 'https');
	if ($scheme && in_array($scheme, $protocols)) {
		$variables['path'] = '//' . file_uri_target($variables['path']);
	}
}

function theblock_views_infinite_scroll_pager($vars) {
	global $base_url;
	$vars['img_path'] = $base_url . '/' . drupal_get_path('theme', 'theblock') . '/images/spinner.gif';
	return theme_views_infinite_scroll_pager($vars);
}

function theblock_textarea($variables) {
	$element = $variables['element'];
	$element['#attributes']['name'] = $element['#name'];
	$element['#attributes']['id'] = $element['#id'];
	$element['#attributes']['cols'] = $element['#cols'];
	$element['#attributes']['rows'] = $element['#rows'];
	$element['#resizable'] = false ;
	_form_set_class($element, array('form-textarea'));
	
	$wrapper_attributes = array(
	'class' => array('form-textarea-wrapper'),
	);
	
	$output = '<div' . drupal_attributes($wrapper_attributes) . '>';
	$output .= '<textarea' . drupal_attributes($element['#attributes']) . '>' . check_plain($element['#value']) . '</textarea>';
	$output .= '</div>';
	return $output;
}

function theblock_advanced_forum_subforum_list(&$variables) {
	$subforums = array();
	foreach ($variables['subforum_list'] as $tid => $subforum) {
		$text = l($subforum->name, "forum/$tid");
		$text .= ' (' . $subforum->total_posts;

		if (empty($subforum->new_posts)) {
			$text .= ')';
		} else {
			$text .= ' - ' . l($subforum->new_posts_text, $subforum->new_posts_path, array('fragment' => 'new')) . ')';
		}

		$subforums[] = $text;
	}
	return implode('<br />', $subforums);
}

function theblock_preprocess_comment(&$variables) {
	$variables['submitted'] = t('!username on !datetime', array('!username' => $variables['author'], '!datetime' => $variables['created']));
}

function theblock_feed_icon($variables) {
	$text = t('Subscribe to @feed-title', array('@feed-title' => $variables['title']));
	if ($image = theme('image', array('path' => 'misc/feed.png', 'width' => 16, 'height' => 16, 'alt' => $text))) {
		$image = theme('image', array('path' => '/sites/all/themes/theblock/images/feed.svg', 'width' => 13, 'height' => 14, 'alt' => $text));
		return l($image, $variables['url'], array('html' => TRUE, 'attributes' => array('class' => array('feed-icon'), 'title' => $text)));
	}
}

function theblock_tablesort_indicator($variables) {
	if ($variables['style'] == "asc") {
		return theme('image', array('path' => '/sites/all/themes/theblock/images/arrow-asc.svg', 'alt' => t('sort ascending'), 'title' => t('sort ascending')));
	} else {
		return theme('image', array('path' => '/sites/all/themes/theblock/images/arrow-desc.svg', 'alt' => t('sort descending'), 'title' => t('sort descending')));
	}
}

function theblock_form_alter(&$form, $form_state, $form_id) {
    // Normally a switch is used because you may want to alter more than
    // one form and it is easy to add a new case for each form.
	switch ($form_id) {
		// This is our form ID.
		case 'forum_node_form':
			$form['actions']['submit']['#value'] = t('Post New Thread');
		break;
		case 'vehicles_node_form':
			$form['actions']['submit']['#value'] = t('Create Vehicle');
		break;
		case 'statuses_node_form':
			$form['actions']['submit']['#value'] = t('Update Status');
		break;
		case 'album_node_form':
			$form['actions']['submit']['#value'] = t('Create Photo Album');
		break;
		case 'competition_node_form':
			$form['actions']['submit']['#value'] = t('Create Community Event');
		break;
		case 'parts_node_form':
			$form['actions']['submit']['#value'] = t('Create Part');
		break;
		
		case 'comment_node_forum_form':
			$form['actions']['submit']['#value'] = t('Post Reply');
		break;
		case 'comment_node_vehicles_form':
			$form['actions']['submit']['#value'] = t('Post Comment');
		break;
		
		case 'user_relationships_ui_request':
			$user=user_load(arg(1));
			drupal_set_title(t('Follow ' . $user->name));
			$form['description']['#markup'] = t("Are you sure you want to become " . $user->name . "'s follower?");
			$form['actions']['submit']['#value'] = t('Follow');
		break;
		
		case 'user_login':
    case 'user_register_form':
      $link = fboauth_action_link_properties('connect');
      $connect_button = theme_image(array(
        'path' => '//www.facebook.com/images/fbconnect/login-buttons/connect_light_large_long.gif',
        'alt' => 'Facebook Connect',
      ));
      $form['fboauth'] = array(
        '#type' => 'markup',
        '#markup' => l($connect_button, $link['href'], array('query' => $link['query'], 'html' => 'TRUE')),
        '#weight' => -3,
      );
    break;
	}
}

function theblock_file($variables) {
	$element = $variables['element'];
	$element['#attributes']['type'] = 'file';
	element_set_attributes($element, array('id', 'name', 'size'));
	$element['#attributes']['size'] = '15';
	_form_set_class($element, array('form-file'));

	return '<input' . drupal_attributes($element['#attributes']) . ' />';
}

function theblock_page_alter(&$page) {
	foreach (system_region_list($GLOBALS['theme'], REGIONS_ALL) as $region => $name) {
		if (in_array($region, array('sidebar_first'))) {
			$page['sidebar_first'] = array(
				'#region' => 'sidebar_first',
				'#weight' => '-10',
				'#theme_wrappers' => array('region'),
			);
		}
		if (in_array($region, array('footer'))) {
			$page['footer'] = array(
				'#region' => 'footer',
				'#weight' => '-10',
				'#theme_wrappers' => array('region'),
			);
		}
	}
}

/**
 * Preprocess and Process Functions SEE: http://drupal.org/node/254940#variables-processor
 * 1. Rename each function and instance of "adaptivetheme_subtheme" to match
 *    your subthemes name, e.g. if your theme name is "footheme" then the function
 *    name will be "footheme_preprocess_hook". Tip - you can search/replace
 *    on "adaptivetheme_subtheme".
 * 2. Uncomment the required function to use.
 * 3. Read carefully, especially within adaptivetheme_subtheme_preprocess_html(), there
 *    are extra goodies you might want to leverage such as a very simple way of adding
 *    stylesheets for Internet Explorer and a browser detection script to add body classes.
 */

/**
 * Override or insert variables into the html templates.
 */
function theblock_preprocess_html(&$vars) {
	//load_subtheme_media_queries($media_queries_css, 'adaptivetheme_subtheme');
	
	// Insert our custom META tags here
  drupal_add_html_head(array('#tag' => 'meta', '#attributes' => array('name' => 'apple-mobile-web-app-status-bar-style', 'content' =>  'black')), 'meta_applemobilewebappstatusbarstyle');
  drupal_add_html_head(array('#tag' => 'meta', '#attributes' => array('name' => 'msvalidate.01', 'content' =>  '843D096421DC3CAE44C929E00CABA43A')), 'meta_msvalidate01');
  
  // Insert OpenSearch description XML link
  drupal_add_html_head(array('#tag' => 'link', '#attributes' => array('type' => 'application/opensearchdescription+xml', 'rel' =>  'search', 'href' => '/sites/all/themes/theblock/opensearch')), 'opensearchdescription');
}

function theblock_preprocess_username(&$variables) {
  $account = $variables['account'];

  $variables['extra'] = '';
  if (empty($account->uid)) {
    $variables['uid'] = 0;
    if (theme_get_setting('toggle_comment_user_verification')) {
      $variables['extra'] = ' (' . t('not verified') . ')';
    }
  }
  else {
    $variables['uid'] = (int) $account->uid;
  }

  // Set the name to a formatted name that is safe for printing and
  // that won't break tables by being too long. Keep an unshortened,
  // unsanitized version, in case other preprocess functions want to implement
  // their own shortening logic or add markup. If they do so, they must ensure
  // that $variables['name'] is safe for printing.
  $name = $variables['name_raw'] = format_username($account);
  //if (drupal_strlen($name) > 20) {
  //  $name = drupal_substr($name, 0, 15) . '...';
  //}
  $variables['name'] = check_plain($name);

  $variables['profile_access'] = user_access('access user profiles');
  $variables['link_attributes'] = array();
  // Populate link path and attributes if appropriate.
  if ($variables['uid'] && $variables['profile_access']) {
    // We are linking to a local user.
    $variables['link_attributes'] = array('title' => t('View user profile.'));
    $variables['link_path'] = 'user/' . $variables['uid'];
  }
  elseif (!empty($account->homepage)) {
    // Like the 'class' attribute, the 'rel' attribute can hold a
    // space-separated set of values, so initialize it as an array to make it
    // easier for other preprocess functions to append to it.
    $variables['link_attributes'] = array('rel' => array('nofollow'));
    $variables['link_path'] = $account->homepage;
    $variables['homepage'] = $account->homepage;
  }
  // We do not want the l() function to check_plain() a second time.
  $variables['link_options']['html'] = TRUE;
  // Set a default class.
  $variables['attributes_array'] = array('class' => array('username'));
}

function theblock_fboauth_action__connect($variables) {
  $action = $variables['action'];
  $link = $variables['properties'];
  $url = url($link['href'], array('query' => $link['query']));
  $link['attributes']['class'] = isset($link['attributes']['class']) ? $link['attributes']['class'] : 'facebook-action-connect';
  $link['attributes']['rel'] = 'nofollow';
  $attributes = isset($link['attributes']) ? drupal_attributes($link['attributes']) : '';
  $title = isset($link['title']) ? check_plain($link['title']) : '';
  $src = ($GLOBALS['is_https'] ? 'https' : 'http') . '://www.facebook.com/images/fbconnect/login-buttons/connect_light_medium_short.gif';
  return 'Experience all The BLOCK has to offer; connect to Facebook now! <a ' . $attributes . ' href="' . $url . '"><img src="' . $src . '" alt="Facebook Connect" /></a>';
}

function theblock_views_pre_render(&$view) {
  if ($view->name == 'frontpage') {  
    $results = &$view->result;
    
    //print_r($view);
    foreach ($results as $key => $result) {
      //print_r($results[$key]->comment_count['rendered']);
    }
  }
}
