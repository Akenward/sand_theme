<?php

/**
 * Implements template_preprocess_node, replacing selected static 
 * field values with form elements that allow them to be edited.
 */
function sand_theme_preprocess_node(&$variables) {
  // Only applies to specific node type 'request'
  if($variables['type'] == 'request') {
  	//We don't need to show comments or links with this form
  	if (isset($variables['content']['comments'])) {
  	  unset ($variables['content']['comments']);
	}
	if (isset($variables['content']['links'])) {
  	  unset ($variables['content']['links']);
	}
  	// Call the form builder function for 'request_updater'
  	// with current node->nid as an extra parameter 
	$status_form = drupal_get_form('request_updater', $variables['nid']); 
	$weight_max = -999;
	// Check whether there is a returned form that can be displayed
	if ($status_form['#access']) {
	  foreach ($status_form['fields_replaced']['#value'] as $field) {
	  	// For each field in the form with a corresponding static field that has
	  	// been populated, copy the weight of the static field to the form
	  	// element and delete the static element.
		if (isset($variables['content'][$field])) {
	  	  $status_form[$field]['#weight'] = $variables['content'][$field]['#weight'];
		  $weight_max = max($weight_max, $status_form[$field]['#weight']);
	  	  unset($variables['content'][$field]);
		}
		// Where the static field hasn't been populated yet, we have to find the weight
		// that would have been used from the field instance record 
		else {
		  $inst = field_info_instance('node', $field, 'request');
		  $status_form[$field]['#weight'] = $inst['display']['default']['weight'];
		  $weight_max = max($weight_max, $status_form[$field]['#weight']);
		}
	  }
	  // Node fields that have been populated and not matched to a form element just stay 
	  // as they are
	  foreach ($variables['content'] as $field => $status) {
		if (isset($variables['content'][$field]['#weight'])) {
		  $weight_max = max($weight_max, $variables['content'][$field]['#weight']);
		}
	  }
	  // Merge the elements of the form with the remaining content elements into
	  // the content array. 
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