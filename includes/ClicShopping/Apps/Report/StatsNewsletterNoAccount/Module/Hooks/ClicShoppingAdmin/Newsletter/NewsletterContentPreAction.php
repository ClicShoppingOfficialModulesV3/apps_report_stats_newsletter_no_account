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

  class NewsletterContentPreAction implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;
    protected $languageId;
    protected $customerGroupId;

    public function __construct()
    {
      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');

      if (isset($_GET['ana'])) {
        $this->newsletterNoAccount = HTML::sanitize($_GET['ana']);
        $this->languageId = HTML::sanitize($_GET['nlID']);
        $this->customerGroupId = HTML::sanitize($_GET['cgID']);
      } else {
        $this->newsletterNoAccount = false;
      }
    }

    public function checkCustomerNoAccount()
    {
      if (!defined('CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS') || CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS == 'False') {
        return false;
      }

      if ($this->newsletterNoAccount == 1 && $this->newsletterNoAccount !== false) {
        if ($this->languageId == 0 && $this->customerGroupId == 0) {
          $QmailNoAccount = $this->app->db->prepare('select count(*) as count_no_account
                                                   from :table_newsletters_no_account
                                                   where customers_newsletter = 1
                                                  ');
          $QmailNoAccount->execute();

          $count_customer_no_account = $QmailNoAccount->valueInt('count_no_account');

        } elseif ($this->customerGroupId == 0) {

          $QmailNoAccount = $this->app->db->prepare('select count(*) as count_no_account
                                                   from :table_newsletters_no_account
                                                   where customers_newsletter = 1
                                                   and languages_id = :languages_id
                                                  ');
          $QmailNoAccount->bindInt(':languages_id', (int)$this->languageId);

          $QmailNoAccount->execute();

          $count_customer_no_account = $QmailNoAccount->valueInt('count_no_account');
        }
      } else {
        $count_customer_no_account = false;
      }

      return $count_customer_no_account;
    }


    public function display()
    {

      if ($this->checkCustomerNoAccount() !== false) {
        $content = '<div><strong>' . $this->app->getDef('text_count_customers_no_account') . ' : ' . $this->checkCustomerNoAccount() . '</strong></div>';

        $output = <<<EOD
<!-- ######################## -->
<!--  Start Newsletter no account Preaction Hooks      -->
<!-- ######################## -->
<script>
$('#newsletterCount').append(
    '{$content}'
);
</script>

<!-- ######################## -->
<!--  End Newsletter no account Preaction App      -->
<!-- ######################## -->

EOD;
        return $output;
      }
    }
  }