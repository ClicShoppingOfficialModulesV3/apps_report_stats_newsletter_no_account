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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Module\Hooks\ClicShoppingAdmin\Customers;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Report\StatsNewsletterNoAccount\StatsNewsletterNoAccount as StatsNewsletterNoAccountApp;

  class Save implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {

      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');
    }

    public function deleteAccount()
    {
      if (isset($_POST['customers_email_address'])) {
        $customers_email_address = HTML::sanitize($_POST['customers_email_address']);

        $QcheckEmailNoAccount = $this->app->db->prepare('select count(*) as total
                                                          from :table_newsletters_no_account
                                                          where customers_email_address = :customers_email_address
                                                        ');
        $QcheckEmailNoAccount->bindValue(':customers_email_address', $customers_email_address);
        $QcheckEmailNoAccount->execute();

        if ($QcheckEmailNoAccount->valueInt('total') > 0) {
          $Qdelete = $this->app->db->prepare('delete
                                              from :table_newsletters_no_account
                                              where customers_email_address = :email_address
                                            ');
          $Qdelete->bindValue(':email_address', $customers_email_address);
          $Qdelete->execute();
        }
      }
    }

    public function execute()
    {
      $this->deleteAccount();
    }
  }