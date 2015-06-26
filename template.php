<?php

/**
 * Implements template_preprocess_node
 * 
 */
function sand_theme_preprocess_node(&$variables) {
  // Only modify nodes of type 'request'
  if($variables['type'] == 'request') {
  	// Call the form builder function for 'request_updater' with current node->nid as an extra parameter 
	$status_form = drupal_get_form('request_updater', $variables['nid']);
	// Check whether there is a returned form that can be displayed
	if ($status_form['#access']) {
	  // Add the form to the 'content'array so it will be rendered as part of the node
	  // Set a 'weight' value that puts the form where it belongs in the rendering sequence
	  // and give it a CSS class.
	  $variables['content']['sand_theme_form'] = $status_form;
	  $variables['content']['sand_theme_form']['#weight'] = 99;
	  $variables['content']['sand_theme_form']['#attributes']['class'] = 'sand-theme-form';
	  // Remove the static content values that are replaced by editable data in the form
	  // from the renderable content array.
	  foreach ($status_form['fields_replaced']['#value'] as $field) {
	  	unset($variables['content'][$field]);
	  }
	}
  }
}
