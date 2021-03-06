<?php

/**
 * @file
 * Contains \Drupal\workbench_access\AccessControlHierarchyInterface.
 */

namespace Drupal\workbench_access;

use Drupal\workbench_access\WorkbenchAccessManager;
use Drupal\workbench_access\WorkbenchAccessManagerInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Defines a base hierarchy class that others may extend.
 */
interface AccessControlHierarchyInterface {

  /**
   * Returns the id for a hierarchy.
   *
   * @return string
   */
  public function id();

  /**
   * Returns the label for a hierarchy.
   *
   * @return string
   */
  public function label();

  /**
   * Returns the status of a hierarchy.
   *
   * @return boolean
   */
  public function status();

  /**
   * Gets the options for a hierarchy.
   *
   * @return array
   *   In the format id => label.
   */
  public function options();

  /**
   * Gets the entire hierarchy tree.
   *
   * @return array
   */
  public function getTree();

  /**
   * Gets the fields that may be used for a plugin type.
   *
   * This method informs the system what fields are eligible to use for
   * access controls. For instance, with taxonomy, it returns all taxonomy
   * reference fields.
   *
   * @param $entity_type
   *   The type of entity access control is being tested for (e.g. 'node').
   * @param $bundle
   *   The entity bundle being tested (e.g. 'article').
   * @param $parents
   *   The selected parent roots of the hierarchy. e.g. a taxonomy vocabulary.
   *   The array contains the ids of the root items (e.g. a vocabulary id).
   *
   * @return array
   *   An array of fields in the format id => label, for use in a form.
   */
  public function getFields($entity_type, $bundle, $parents);

  /**
   * Retrieves the access control values from an entity.
   *
   * @param EntityInterface $entity
   *   A Drupal entity, typically a node or a user.
   * @param $field
   *   The field holding the access control data.
   *
   * @return array
   *   An array of field data from the entity.
   */
  public function getEntityValues(EntityInterface $entity, $field);

  /**
   * Loads a hierarchy definition for a single item in the tree.
   *
   * @param $id
   *   The identifier for the item, such as a term id.
   *
   * @return \Drupal\workbench_access\AccessControlHierarchyInterface
   *   A plugin implementation.
   */
  public function load($id);

  /**
   * Provides configuration options.
   *
   * @param $scheme
   *   The id of an access control scheme.
   * @param $parents
   *   The selected parent roots of the hierarchy. e.g. a taxonomy vocabulary.
   *   The array contains the ids of the root items (e.g. a vocabulary id).
   */
  public function configForm($scheme, $parents = array());

  /**
   * Validates configuration options.
   *
   * @param array &$form
   *   The submitted form, passed by reference.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   *
   * @return
   *   No return value. Interact with the $form_state object.
   */
  public function configValidate(array &$form, FormStateInterface $form_state);

  /**
   * Submits configuration options.
   *
   * @param array &$form
   *   The submitted form, passed by reference.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   The form state object.
   */
  public function configSubmit(array &$form, FormStateInterface $form_state);

  /**
   * Responds to request for node access.
   *
   * @param EntityInterface $entity
   *   The node being checked. In future this may handle other entity types.
   * @param AccountInterface $account
   *   The user requesting access to the node.
   * @param WorkbenchAccessManagerInterface $manager
   *   The access control manager.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   An access result response. By design, this is either neutral or deny.
   *
   * @see workbench_access_node_access()
   */
  public function checkEntityAccess(EntityInterface $entity, $op, AccountInterface $account, WorkbenchAccessManagerInterface $manager);

  /**
   * Alter the selection options provided for an access control field.
   *
   * @param $field
   *   The field element from a node form.
   * @param WorkbenchAccessManagerInterface $manager
   *   The access manager.
   *
   * @return $element
   *   The field element, after restricting selection options.
   */
  public function alterOptions($field, WorkbenchAccessManagerInterface $manager);

}
