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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Module\Hooks\ClicShoppingAdmin\Newsletter;

  use ClicShopping\OM\Registry;
  use ClicShopping\OM\HTML;

  use ClicShopping\Apps\Report\StatsNewsletterNoAccount\StatsNewsletterNoAccount as StatsNewsletterNoAccountApp;


  class NewsletterSend implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;
    protected $languageId;
    protected $customerGroupId;
    protected $newsletterNoAccount;
    protected $emailFrom;

    public function __construct()
    {
      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');

      if (isset($_GET['ana'])) {
        $this->emailFrom = htmlentities($this->app->getDef('email_from'));
        $this->title =
        $this->languageId = HTML::sanitize($_GET['nlID']);
        $this->customerGroupId = HTML::sanitize($_GET['cgID']);
        $this->newsletterNoAccount = HTML::sanitize($_GET['ana']);

      } else {
        $this->newsletterNoAccount = false;
      }
    }

    public function newsletter($newsletter_id)
    {
      $CLICSHOPPING_Mail = Registry::get('Mail');

      if (isset($newsletter_id) && $this->newsletterNoAccount !== false) {
        $max_execution_time = 0.8 * (int)ini_get('max_execution_time');
        $time_start = explode(' ', PAGE_PARSE_START_TIME);

        if ($this->customerGroupId == 0 && $this->languageId == 0) {
          $QmailNoAccount = $this->app->db->prepare('select customers_firstname,
                                                              customers_lastname,
                                                              customers_email_address
                                                       from :table_newsletters_no_account
                                                       where customers_newsletter = 1
                                                    ');
          $QmailNoAccount->execute();

        } elseif ($this->customerGroupId == 0) {
          $QmailNoAccount = $this->app->db->prepare('select customers_firstname,
                                                            customers_lastname,
                                                            customers_email_address
                                                     from :table_newsletters_no_account
                                                     where customers_newsletter = 1
                                                     and languages_id = :languages_id
                                                    ');
          $QmailNoAccount->bindInt(':languages_id', $this->languageId);
          $QmailNoAccount->execute();
        }

        $Qnewsletters = $this->app->db->prepare('select newsletters_id,
                                                        title,
                                                        content
                                                from :table_newsletters
                                                where newsletters_id = :newsletters_id
                                                ');
        $Qnewsletters->bindInt(':newsletters_id', $this->newsletterId);
        $Qnewsletters->execute();

        $title = $Qnewsletters->value('title');
        $content = $Qnewsletters->value('content');
        if ($this->newsletterNoAccount == 1 && $this->customerGroupId == 0) {
// copy e-mails to a temporary table if that table is empty
          $Qcheck = $this->app->db->prepare('select count(customers_email_address) as num_customers_email_address
                                            from :table_newsletters_customers_temp
                                         ');
          $Qcheck->execute();

          if ($Qcheck->fetch()) {
            while ($QmailNoAccount->fetch()) {
              if (preg_match("#^[-a-z0-9._]+@([-a-z0-9_]+\.)+[a-z]{2,6}$#i", $QmailNoAccount->value('customers_email_address'))) {
                $this->app->db->save('newsletters_customers_temp', ['customers_firstname' => addslashes($QmailNoAccount->value('customers_firstname')),
                    'customers_lastname' => addslashes($QmailNoAccount->value('customers_lastname')),
                    'customers_email_address' => $QmailNoAccount->value('customers_email_address')
                  ]
                );
              }
            }
          } else {
            echo 'There is a problem with the database table : newsletters_customers_temp, please send an email to your administrator.<br />';
          } // end mysql num

          $QmailNewsletterAccountTemp = $this->app->db->prepare('select customers_firstname,
                                                                        customers_lastname,
                                                                        customers_email_address
                                                               from :table_newsletters_customers_temp
                                                              ');
          $QmailNewsletterAccountTemp->execute();

          while ($QmailNewsletterAccountTemp->fetch()) {
            $time_end = explode(' ', microtime());
            $timer_total = number_format(($time_end[1] + $time_end[0] - ($time_start[1] + $time_start[0])), 3);

            if ($timer_total > $max_execution_time) {
              echo("<meta http-equiv=\"refresh\" content=\"12\">");
            }

            $CLICSHOPPING_Mail->send($QmailNewsletterAccountTemp->value('customers_firstname') . ' ' . $QmailNewsletterAccountTemp->value('customers_lastname'), $QmailNewsletterAccountTemp->value('customers_email_address'), '', $this->emailFrom, $title);
// delete all entry in the table
            $Qdelete = $this->app->db->prepare('delete
                                              from :table_newsletters_customers_temp
                                              where customers_email_address = :customers_email_address
                                              ');
            $Qdelete->bindValue(':customers_email_address', $QmailNewsletterAccountTemp->value('customers_email_address'));
            $Qdelete->execute();
          } //end while
        }
      }
    }

    public function execute()
    {
      if (!defined('CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS') || CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS == 'False') {
        return false;
      }

      if ($this->newsletterNoAccount == 0 || $this->newsletterNoAccount === false) {
        return false;
      }

      if (isset($_GET['nID'])) {
        $newsletter_id = HTML::sanitize($_GET['nID']);
        $this->newsletter($newsletter_id);
      }
    }
  }