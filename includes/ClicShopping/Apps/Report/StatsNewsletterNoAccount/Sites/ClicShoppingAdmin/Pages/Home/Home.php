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

  namespace ClicShopping\Apps\Report\StatsNewsletterNoAccount\Sites\ClicShoppingAdmin\Pages\Home;

  use ClicShopping\OM\Registry;

  use ClicShopping\Apps\Report\StatsNewsletterNoAccount\StatsNewsletterNoAccount;

  class Home extends \ClicShopping\OM\PagesAbstract
  {
    public mixed $app;

    protected function init()
    {
      $CLICSHOPPING_StatsNewsletterNoAccount = new StatsNewsletterNoAccount();
      Registry::set('StatsNewsletterNoAccount', $CLICSHOPPING_StatsNewsletterNoAccount);

      $this->app = Registry::get('StatsNewsletterNoAccount');

      $this->app->loadDefinitions('Sites/ClicShoppingAdmin/main');
    }
  }
