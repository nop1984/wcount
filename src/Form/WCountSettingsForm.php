<?php

namespace Drupal\wcount\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Routing\RequestContext;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Cache\Cache;


/**
 * Defines a form that configures FB Int Connect settings.
 */
class WCountSettingsForm extends ConfigFormBase {

  protected $requestContext;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Routing\RequestContext $request_context
   *   Holds information about the current request.
   */
  public function __construct(ConfigFactoryInterface $config_factory, RequestContext $request_context) {
    $this->setConfigFactory($config_factory);
    $this->requestContext = $request_context;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Instantiates this class.
    return new static(
      // Load the services required to construct this class.
      $container->get('config.factory'),
      $container->get('router.request_context')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'wcount_admin_form';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      \Drupal\wcount\WCOUNT_SETTINGS,
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(\Drupal\wcount\WCOUNT_SETTINGS);
    $groupname = \Drupal\wcount\WCOUNT_SETTINGS;

    $form[$groupname] = [
      '#type' => 'details',
      '#title' => $this->t('Word Counter Module Settings'),
      '#open' => TRUE,
      '#description' => $this->t('Word Counter Module Settings'),
    ];

    $form[$groupname][\Drupal\wcount\BODY_FIELDNAME] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#disabled' => !empty($config->get(\Drupal\wcount\BODY_FIELDNAME)),
      '#title' => $this->t('Body field name in Node'),
      '#default_value' => $config->get(\Drupal\wcount\BODY_FIELDNAME),
      '#description' => $this->t('Change if you beleive in magic and have no backup.'),
    ];

    $form[$groupname][\Drupal\wcount\COUNTER_FIELDNAME] = [
      '#type' => 'textfield',
      '#required' => TRUE,
      '#disabled' => !empty($config->get(\Drupal\wcount\COUNTER_FIELDNAME)),
      '#title' => $this->t('Counter Field Name'),
      '#default_value' => $config->get(\Drupal\wcount\COUNTER_FIELDNAME),
      '#description' => $this->t('Change if you beleive in magic and have no backup.'),
    ];

    $form[$groupname][\Drupal\wcount\METHOD] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Use Drupal field or plain SQL table with virtual Drupal field'),
      '#default_value' => $config->get(\Drupal\wcount\METHOD),
      '#description' => $this->t('Drupal field has cons, as Node must be saved also when Word Count is updated, however is more native. Developer likes plain SQL method more'),
      '#options' => [
        \Drupal\wcount\METHOD_DRUPAL_FIELD => $this->t('Native Drupal Field'), 
        \Drupal\wcount\METHOD_PLAIN_SQL => $this->t('Plain SQL Table')
        ]
    ];

    $form[$groupname][\Drupal\wcount\SUSPEND] = [
      '#type' => 'select',
      '#required' => TRUE,
      '#title' => $this->t('Suspend module work'),
      '#description' => $this->t('If you suspend module word counter will not be shown. Also new nodes will not get counter filled until recalculation is not invoked manually'),
      '#default_value' => $config->get(\Drupal\wcount\SUSPEND),
      '#options' => [\Drupal\wcount\SUSPEND_WORKING => $this->t('Working and Showing'), \Drupal\wcount\SUSPEND_SUSPENDED => 'Suspend the module']
    ];

    $form[$groupname]['recalc'] = [
        '#type' => 'markup',
        '#markup' => $this->t('<b>Here should be a button to recalculate Word Count for nodes which miss it, but its not required by test task</b>')
    ];


    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValues();
    $this->config(\Drupal\wcount\WCOUNT_SETTINGS)
      ->set(\Drupal\wcount\METHOD, $values[\Drupal\wcount\METHOD])
      ->set(\Drupal\wcount\SUSPEND, $values[\Drupal\wcount\SUSPEND])
      ->save();

    parent::submitForm($form, $form_state);

    drupal_flush_all_caches();
  }

}
