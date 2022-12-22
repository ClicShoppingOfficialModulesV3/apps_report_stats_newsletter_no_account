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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Sites\ClicShoppingAdmin\Pages\Home\Actions;

  use ClicShopping\OM\Registry;

  class StatsNewsletterNoAccount extends \ClicShopping\OM\PagesActionsAbstract
  {
    public function execute()
    {
      $CLICSHOPPING_StatsNewsletterNoAccount = Registry::get('StatsNewsletterNoAccount');

      $this->page->setFile('stats_newsletter_no_account.php');
      $this->page->data['action'] = 'StatsNewsletterNoAccount';

      $CLICSHOPPING_StatsNewsletterNoAccount->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }