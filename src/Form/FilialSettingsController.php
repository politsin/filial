<?php

namespace Drupal\filial\Form;

use Drupal\Component\Utility\Html;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\node\Entity\NodeType;
use Drupal\comment\Entity\CommentType;
use Drupal\Core\Extension\ModuleHandlerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\EntityTypeBundleInfoInterface;
use Drupal\Core\Cache\CacheBackendInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Returns responses for Honeypot module routes.
 */
class FilialSettingsController extends ConfigFormBase {

  /**
   * The module handler service.
   *
   * @var \Drupal\Core\Extension\ModuleHandlerInterface
   */
  protected $moduleHandler;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity type bundle info service.
   *
   * @var \Drupal\Core\Entity\EntityTypeBundleInfoInterface
   */
  protected $entityTypeBundleInfo;

  /**
   * A cache backend interface.
   *
   * @var \Drupal\Core\Cache\CacheBackendInterface
   */
  protected $cache;

  /**
   * Constructs a settings controller.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Extension\ModuleHandlerInterface $module_handler
   *   The module handler.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Entity\EntityTypeBundleInfoInterface $entity_type_bundle_info
   *   The entity type bundle info service.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache_backend
   *   The cache backend interface.
   */
  public function __construct(ConfigFactoryInterface $config_factory, ModuleHandlerInterface $module_handler, EntityTypeManagerInterface $entity_type_manager, EntityTypeBundleInfoInterface $entity_type_bundle_info, CacheBackendInterface $cache_backend) {
    parent::__construct($config_factory);
    $this->moduleHandler = $module_handler;
    $this->entityTypeManager = $entity_type_manager;
    $this->entityTypeBundleInfo = $entity_type_bundle_info;
    $this->cache = $cache_backend;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('module_handler'),
      $container->get('entity_type.manager'),
      $container->get('entity_type.bundle.info'),
      $container->get('cache.default')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['filial.nodesettings'];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'filial_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    // If contact.module enabled, add contact forms.
    $config = $this->config('filial.nodesettings');
    $form['general'] = [
      '#type' => 'details',
      '#title' => $this->t('General settings'),
      '#open' => TRUE,
    ];

    $form["general"]['default-filial'] = [
      '#title' => $this->t('Default filial id'),
      '#type' => 'number',
      '#min' => 0,
      '#step' => 1,
      '#size' => 15,
      '#default_value' => $config->get('default-filial'),
    ];
    $form["general"]['filial'] = [
      '#title' => $this->t('Filial'),
      '#description' => $this->t('Set Filial'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('filial'),
    ];
    $form["general"]['client'] = [
      '#title' => $this->t('Client'),
      '#description' => $this->t('field_client'),
      '#type' => 'checkbox',
      '#default_value' => $config->get('client'),
    ];
    // Node types for node forms.
    if ($this->moduleHandler->moduleExists('node')) {
      $types = NodeType::loadMultiple();
      if (!empty($types)) {
        // Node forms.
        $form['form_settings']['node_forms'] = ['#markup' => '<h5>' . $this->t('Node Forms') . '</h5>'];
        foreach ($types as $type) {
          $id = 'node_' . $type->get('type');
          $form['form_settings'][$id] = [
            '#type' => 'checkbox',
            '#title' => $this->t('@name node form', ['@name' => $type->label()]),
            '#default_value' => $config->get($id),
          ];
        }
      }
    }
    if ($this->moduleHandler->moduleExists('contact')) {
      $form['form_settings']['contact_forms'] = ['#markup' => '<h5>' . $this->t('Contact Forms') . '</h5>'];

      $bundles = $this->entityTypeBundleInfo->getBundleInfo('contact_message');
      $formController = $this->entityTypeManager->getFormObject('contact_message', 'default');

      foreach ($bundles as $bundle_key => $bundle) {
        $stub = $this->entityTypeManager->getStorage('contact_message')->create([
          'contact_form' => $bundle_key,
        ]);
        $formController->setEntity($stub);
        $form_id = $formController->getFormId();

        $form['form_settings'][$form_id] = [
          '#type' => 'checkbox',
          '#title' => Html::escape($bundle['label']),
          '#default_value' => $config->get($form_id),
        ];
      }
    }

    // For now, manually add submit button. Hopefully, by the time D8 is
    // released, there will be something like system_settings_form() in D7.
    $form['actions']['#type'] = 'container';
    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Save configuration'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('filial.nodesettings');
    $config->set('default-filial', $form_state->getValue('default-filial'));
    $config->set('filial', $form_state->getValue('filial'));
    $config->set('client', $form_state->getValue('client'));
    foreach ($form_state->getValues() as $key => $value) {
      if (substr($key, 0, 16) == 'contact_message_') {
        $config->set($key, $value);
      }
      if (substr($key, 0, 5) == 'node_') {
        $config->set($key, $value);
      }
    }
    $config->save();
  }

}
