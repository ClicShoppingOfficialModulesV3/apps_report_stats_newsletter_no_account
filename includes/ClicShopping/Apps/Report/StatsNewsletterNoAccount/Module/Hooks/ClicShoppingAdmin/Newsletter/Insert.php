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

  class Insert implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');
    }

    public function newsletter()
    {
      if (isset($_POST['newsletters_customer_no_account'])) {
        $newsletters_customer_no_account = HTML::sanitize($_POST['newsletters_customer_no_account']);

        if ($newsletters_customer_no_account == 'on') {
          $newsletters_customer_no_account = 1;
        } else {
          $newsletters_customer_no_account = 0;
        }

        $Qnewsletter = $this->app->db->prepare('select newsletters_id
                                                from :table_newsletters
                                                order by newsletters_id
                                                desc limit 1
                                                ');

        $Qnewsletter->execute();

        $sql_data_array = ['newsletters_customer_no_account' => (int)$newsletters_customer_no_account];
        $insert_array = ['newsletters_id' => $Qnewsletter->valueInt('newsletters_id')];

        $this->app->db->save('newsletters', $sql_data_array, $insert_array);
      }
    }

    public function execute()
    {
      if (!defined('CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS') || CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS == 'False') {
        return false;
      }

      $this->newsletter();
    }
  }