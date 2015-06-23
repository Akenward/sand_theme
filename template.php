<?php

/**
 * Implements template_preprocess_node
 * 
 */
function sand_theme_preprocess_node(&$variables) {
	
  // Only modifies nodes of type 'application'
  if($variables['type'] == 'application') {
  	
  	// Calls the form builder function for 'sandbox_updater' with current node->nid as an extra parameter 
	$status_form = drupal_get_form('sandbox_updater', $variables['nid']);
	
	// Checks whether sandbox_updater produced a form or decided not to
	if ($status_form['form_needed']['#value']) {
		
	  /* Add the form to the 'content'array so it will be rendered as part of the node
	  * setting a 'weight' value that puts the form where it belongs in the rendering sequence
	  * and a CSS class.
	  */
	  $variables['content']['sand_theme_form'] = $status_form;
	  $variables['content']['sand_theme_form']['#weight'] = 99;
	  $variables['content']['sand_theme_form']['#attributes']['class'] = 'sand-theme-form';
	  
	  // fields handled in the form no longer need to be rendered as static node values 
	  foreach ($status_form['fields_replaced']['#value'] as $field) {
	  	unset($variables['content'][$field]);
	  }
	}
  }
}
