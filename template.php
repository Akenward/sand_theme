<?php

/**
 * Implements template_preprocess_node, replacing selected static 
 * field values with form elements that allow them to be edited.
 */
function sand_theme_preprocess_node(&$variables) {
  // Only modify nodes of type 'request'
  if($variables['type'] == 'request') {
  	// Call the form builder function for 'request_updater'
  	// with current node->nid as an extra parameter 
	$status_form = drupal_get_form('request_updater', $variables['nid']); 
	$weight_max = -999;
	// Check whether there is a returned form that can be displayed
	if ($status_form['#access']) {
	  // Align the weight values for the each form elements with that of
	  // the correponding static field and then delete the static element
	  foreach ($status_form['fields_replaced']['#value'] as $field) {
	  	$status_form[$field]['#weight'] = $variables['content'][$field]['#weight'];
		$weight_max = max($weight_max, $status_form[$field]['#weight']);
	  	unset($variables['content'][$field]);
	  }
	  foreach ($variables['content'] as $field => $status) {
		if (isset($variables['content'][$field]['#weight'])) {
		  $weight_max = max($weight_max, $variables['content'][$field]['#weight']);
		}
	  }
	  // Merge the elements of the form with the remaining content elements into
	  // the content array.  Could have set #type to 'markup' for each static
	  // field but this is applied as the default #type by Form API, so no need. 
	  $variables['content'] = array_merge($status_form, $variables['content']);
	  // $weight_max should now equal to the largest weight of any content element
	  // so add 1 to make sure that the 'Save' button appears at the bottom 
	  $variables['content']['submit_button']['#weight'] = $weight_max + 1;
	}
    else {
  	  // If there's no form to be displayed, no change is made to the content array 
  	  // and we still have the regular static display for the node
	}
  }
}
