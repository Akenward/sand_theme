<?php

/**
 * Implements template_preprocess_node, replacing selected static 
 * field values with a form that allows them to be edited.
 */
function sand_theme_preprocess_node(&$variables) {
  // Only modify nodes of type 'request'
  if($variables['type'] == 'request') {
  	// Call the form builder function for 'request_updater'
  	// with current node->nid as an extra parameter 
	$status_form = drupal_get_form('request_updater', $variables['nid']);
	// Check whether there is a returned form that can be displayed
	if ($status_form['#access']) {
	  // Set a 'weight' value that puts the form where it belongs 
	  // in the rendering sequence and give it a CSS class.
	  $status_form['#weight'] = 99;
	  $status_form['#attributes']['class'] = 'sand-theme-form';
	  // Add the form to the 'content' array so it will be rendered 
	  // as part of the node.  
	  $variables['content']['sand_theme_form'] = $status_form;
	  // Remove the static field values that we don't need any more
	  // from the content array.
	  foreach ($status_form['fields_replaced']['#value'] as $field) {
	  	unset($variables['content'][$field]);
	  }
	}
  }
}
