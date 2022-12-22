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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Sites\ClicShoppingAdmin\Pages\Home\Actions\StatsNewsletterNoAccount;

  use ClicShopping\OM\Registry;

  class Reset extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_StatsEmailValidation = Registry::get('StatsEmailValidation');

      if (!isset($_POST['resetViewed'])) {
        if ($_POST['resetViewed'] == '0') {

// Reset ALL counts
          $Qdelete = $CLICSHOPPING_StatsEmailValidation->db->prepare('delete
                                                                from :table_newsletters_no_account
                                                              ');
          $Qdelete->execute();
        } else {
// Reset selected product count
          $Qdelete = $CLICSHOPPING_StatsEmailValidation->db->prepare('delete
                                                                from :table_newsletters_no_account
                                                                where customers_email_address = :customers_email_address
                                                              ');
          $Qdelete->bindInt(':customers_email_address', (int)$_GET['resetViewed']);
          $Qdelete->execute();
        }
      }
    }
  }