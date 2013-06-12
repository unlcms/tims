<?php

function tims_edit($form, &$form_state, $template_name) {
  $templates = variable_get('tims_templates', array());
  $template = '';
  if (array_key_exists($template_name, $templates)) {
    $template = $templates[$template_name];
  }

  $form['name'] = array(
    '#type' => 'textfield',
    '#title' => 'Name',
    '#default_value' => ($template_name != '_new_' ? $template_name : NULL),
    '#description' => t('The theme key for this template. See <a href="@link">Working with template suggestions</a>.', array('@link' => url('https://drupal.org/node/223440'))),
    '#required' => TRUE,
    '#disabled' => ($template_name != '_new_'),
  );

  $form['template'] = array(
    '#type' => 'textarea',
    '#title' => 'Template',
    '#default_value' => $template,
    '#rows' => 20,
    '#description' => t('A template using Twig syntax. Refer to the <a href="@link">Theming Drupal 8</a> guide and <a href="@help">this module\'s help page</a>.', array('@link' => url('https://drupal.org/node/1906384'), '@help' => url('admin/help/tims'))),
  );

  $form['submit'] = array(
    '#type' => 'submit',
    '#value' => 'Save',
  );

  return $form;
}

function tims_edit_submit($form, &$form_state) {
  $templates = variable_get('tims_templates', array());
  $templates[$form_state['values']['name']] = $form_state['values']['template'];
  variable_set('tims_templates', $templates);
  $form_state['redirect'] = 'admin/structure/tims';
}

function tims_delete($form, &$form_state, $template_name) {
  $form['name'] = array(
    '#type' => 'hidden',
    '#value' => $template_name,
  );

  return confirm_form(
    $form,
    t('Are you sure you want to delete the template for "%tn"?', array('%tn' => $template_name)),
    'admin/structure/tims',
    t('This action cannot be undone.'),
    t('Delete Template')
  );
}

function tims_delete_submit($form, &$form_state) {
  $templates = variable_get('tims_templates', array());
  unset($templates[$form_state['values']['name']]);
  variable_set('tims_templates', $templates);
  $form_state['redirect'] = 'admin/structure/tims';
}

function tims_list($form, &$form_state) {
  $header = array(
    'name' => array(
      'data' => t('Name'),
      'field' => 'name',
    ),
    'operations' => t('Operations'),
  );

  $templates = variable_get('tims_templates', array());

  $rows = array();
  foreach ($templates as $name => $template) {
    $rows[$name] = array(
      'name' => $name,
      'operations' => array(
        'data' => array(
          '#theme' => 'links__node_operations',
          '#links' => array(
            'edit' => array(
              'title' => t('edit'),
              'href' => 'admin/structure/tims/' . $name . '/edit',
            ),
            'delete' => array(
              'title' => t('delete'),
              'href' => 'admin/structure/tims/' . $name . '/delete',
            ),
          ),
          '#attributes' => array('class' => array('links', 'inline')),
        ),
      ),
    );
  }

  $form['template_list'] = array(
    '#caption' => t('Existing Templates: ') . count($templates),
    '#theme' => 'table',
    '#header' => $header,
    '#rows' => $rows,
    '#empty' => t('No templates have been created.'),
  );

  return $form;
}
