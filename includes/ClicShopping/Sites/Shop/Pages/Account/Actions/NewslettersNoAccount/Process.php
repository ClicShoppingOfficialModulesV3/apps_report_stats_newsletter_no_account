<?php
  /**
   *
   * @copyright 2008 - https://www.clicshopping.org
   * @Brand : ClicShopping(Tm) at Inpi all right Reserved
   * @Licence GPL 2 & MIT
   * @licence MIT - Portion of osCommerce 2.4
   * @Info : https://www.clicshopping.org/forum/trademark/
   *
   */

  namespace ClicShopping\Sites\Shop\Pages\Account\Actions\NewslettersNoAccount;

  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;
  use ClicShopping\OM\Is;

  use ClicShopping\Apps\Tools\ActionsRecorder\Classes\Shop\ActionRecorder;
  use ClicShopping\Apps\Configuration\TemplateEmail\Classes\Shop\TemplateEmail;

  class Process extends \ClicShopping\OM\PagesActionsAbstract
  {

    public function execute()
    {
      $CLICSHOPPING_Db = Registry::get('Db');
      $CLICSHOPPING_Customer = Registry::get('Customer');
      $CLICSHOPPING_MessageStack = Registry::get('MessageStack');
      $CLICSHOPPING_Mail = Registry::get('Mail');
      $CLICSHOPPING_Language = Registry::get('Language');
      $CLICSHOPPING_Hooks = Registry::get('Hooks');

      if (isset($_POST['action']) && ($_POST['action'] == 'process') && isset($_POST['formid']) && ($_POST['formid'] === $_SESSION['sessiontoken'])) {
        $error = false;

        $CLICSHOPPING_Hooks->call('PreAction', 'Process');

        $firstname = HTML::sanitize($_POST['firstname']);
        $lastname = HTML::sanitize($_POST['lastname']);
        $email_address = HTML::sanitize($_POST['email_address']);
        $email_address_confirm = HTML::sanitize($_POST['email_address_confirm']);
        $customer_agree_privacy = HTML::sanitize($_POST['customer_agree_privacy']);

        if (DISPLAY_PRIVACY_CONDITIONS == 'true') {
          if ($customer_agree_privacy != 'on') {
            $error = true;

            $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_agreement_check_error'), 'error', 'create_account');
          }
        }

//recaptcha control
// Controle entree du prenom
        if (strlen($firstname) < ENTRY_FIRST_NAME_MIN_LENGTH) {
          $error = true;

          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_first_name_error'), 'error', 'newsletter_no_account');
        }

// Controle entree du nom de famille
        if (strlen($lastname) < ENTRY_LAST_NAME_MIN_LENGTH) {
          $error = true;

          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_last_name_error'), 'error', 'newsletter_no_account');
        }

// Controle entree adresse e-mail
        if (Is::EmailAddress($email_address) === false) {
          $error = true;
          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_error'), 'error', 'newsletter_no_account');

        } elseif ($email_address != $email_address_confirm) {
          $error = true;

          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_confirm_not_matching'), 'error', 'newsletter_no_account');
        } else {
// email account verify

          $QcheckEmail = $CLICSHOPPING_Db->prepare('select count(*) as total
                                                      from :table_customers
                                                      where customers_email_address = :customers_email_address
                                                    ');
          $QcheckEmail->bindValue(':customers_email_address', $email_address);
          $QcheckEmail->execute();

// email with no account verify

          $QcheckEmailNoAccount = $CLICSHOPPING_Db->prepare('select count(*) as total
                                                                from :table_newsletters_no_account
                                                                where customers_email_address = :customers_email_address
                                                              ');
          $QcheckEmailNoAccount->bindValue(':customers_email_address', $email_address);
          $QcheckEmailNoAccount->execute();

          if ($QcheckEmail->value('total') > 0 || $QcheckEmailNoAccount->value('total') > 0) {
            $error = true;
            $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_error_exists'), 'error', 'newsletter_no_account');
          }
        }

        Registry::set('ActionRecorder', new ActionRecorder('ar_newsletter_no_account', ($CLICSHOPPING_Customer->isLoggedOn() ? $CLICSHOPPING_Customer->getID() : null), $lastname));
        $CLICSHOPPING_ActionRecorder = Registry::get('ActionRecorder');

        if (!$CLICSHOPPING_ActionRecorder->canPerform()) {
          $error = true;
          $CLICSHOPPING_ActionRecorder->record(false);

          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('error_action_recorder', ['module_action_recorder_newsletter_no_account_email_minutes' => (defined('MODULE_ACTION_RECORDER_NEWSLETTER_NO_ACCOUNT_EMAIL_MINUTES') ? (int)MODULE_ACTION_RECORDER_NEWSLETTER_NO_ACCOUNT_EMAIL_MINUTES : 15)]), 'danger', 'newsletter_no_account');
        }

        if ($error === false) {
          $sql_data_array = ['customers_firstname' => $firstname,
            'customers_lastname' => $lastname,
            'customers_email_address' => $email_address,
            'customers_newsletter' => '1',
            'customers_date_added' => 'now()',
            'languages_id' => $CLICSHOPPING_Language->getId()
          ];
          $CLICSHOPPING_Db->save('newsletter_no_account', $sql_data_array);

// build the message content
          $name = $firstname . ' ' . $lastname;
          $message = utf8_decode(CLICSHOPPING::getDef('email_welcome', ['store_name' => STORE_NAME, 'store_owner_email_address' => STORE_OWNER_EMAIL_ADDRESS]));
          $email_welcome = html_entity_decode($message);

          $email_warning = TemplateEmail::getTemplateEmailSignature();

          $message = utf8_decode(CLICSHOPPING::getDef('email_subject', ['store_name' => STORE_NAME]));
          $email_subject = html_entity_decode($message);

          $email_text = $email_welcome . $email_warning;
          $CLICSHOPPING_Mail->clicMail($name, $email_address, $email_subject, $email_text, STORE_NAME, STORE_OWNER_EMAIL_ADDRESS);

          $CLICSHOPPING_ActionRecorder->record();

          $CLICSHOPPING_Hooks->call('NewslettersNoAccount', 'Process');

          CLICSHOPPING::redirect(null, 'Account&NewslettersNoAccount&Success');

        } else {
          $CLICSHOPPING_MessageStack->add(CLICSHOPPING::getDef('entry_email_address_check_error_number'), 'error', 'newsletter_no_account');
          CLICSHOPPING::redirect(null, 'Account&NewslettersNoAccount');
        }
      }
    }
  }