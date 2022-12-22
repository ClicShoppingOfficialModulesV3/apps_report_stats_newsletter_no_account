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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Module\Hooks\ClicShoppingAdmin\StatsDashboard;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Report\StatsNewsletterNoAccount\StatsNewsletterNoAccount as StatsNewsletterNoAccountApp;

  class PageTabContent implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');

      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/StatsDashboard/page_tab_content');
    }

    private function statsCountCustomersNewsletterAnonymous()
    {
      $QcustomersNewsletterNoAccount = $this->app->db->prepare('select count(customers_newsletter) as count
                                                                from :table_newsletters_no_account
                                                                where customers_newsletter = 1
                                                               ');
      $QcustomersNewsletterNoAccount->execute();

      $customers_total_newsletter_no_account = $QcustomersNewsletterNoAccount->valueInt('count');

      return $customers_total_newsletter_no_account;
    }

    public function display()
    {

      if (!defined('CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS') || CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS == 'False') {
        return false;
      }

      if ($this->statsCountCustomersNewsletterAnonymous() != 0) {
        $content = '
          <div class="row">
            <div class="col-md-11 mainTable">
              <div class="form-group row">
                <label for="' . $this->app->getDef('box_entry_newsletter_no_account') . '" class="col-9 col-form-label"><a href="' . $this->app->link('StatsNewsletterNoAccount') . '">' . $this->app->getDef('box_entry_newsletter_no_account') . '</a></label>
                <div class="col-md-3">
                  ' . $this->statsCountCustomersNewsletterAnonymous() . '
                </div>
              </div>
            </div>
          </div>
         ';

        $output = <<<EOD
  <!-- ######################## -->
  <!--  Start NewsletterAnonymous      -->
  <!-- ######################## -->
             {$content}
  <!-- ######################## -->
  <!--  Start NewsletterAnonymous      -->
  <!-- ######################## -->
EOD;
        return $output;
      }
    }
  }