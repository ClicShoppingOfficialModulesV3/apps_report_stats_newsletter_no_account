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

  class NewsletterContentTab1 implements \ClicShopping\OM\Modules\HooksInterface
  {
    protected $app;

    public function __construct()
    {
      if (!Registry::exists('StatsNewsletterNoAccount')) {
        Registry::set('StatsNewsletterNoAccount', new StatsNewsletterNoAccountApp());
      }

      $this->app = Registry::get('StatsNewsletterNoAccount');
      $this->app->loadDefinitions('Module/Hooks/ClicShoppingAdmin/newsletter/page_content_tab_1');
    }

    public function display()
    {
      if (!defined('CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS') || CLICSHOPPING_APP_STATS_NEWSLETTER_NO_ACCOUNT_SA_STATUS == 'False') {
        return false;
      }

      if (isset($_GET['Update'])) {
        $newsletters_customer_no_account = 0;

        if (isset($_GET['nID'])) {
          $nID = HTML::sanitize($_GET['nID']);

          $Qnewsletter = $this->app->db->get('newsletters', ['newsletters_customer_no_account'], ['newsletters_id' => (int)$nID]);

          $newsletters_customer_no_account = $Qnewsletter->valueInt('newsletters_customer_no_account');
        }

        if ($newsletters_customer_no_account == 0) {
          $in_accept_customer_no_account = false;
          $out_accept_customer_no_account = true;
        } else {
          $in_accept_customer_no_account = true;
          $out_accept_customer_no_account = false;
        }

        $content = '<div class="row col-md-12">';
        $content .= '<div class="col-md-9">';
        $content .= '<div class="form-group row">';
        $content .= '<label for="' . $this->app->getDef('text_newsletter_customer_no_account') . '" class="col-5 col-form-label">' . $this->app->getDef('text_newsletter_customer_no_account') . '</label>';
        $content .= '<div class="col-md-5">';
        $content .= HTML::radioField('newsletters_customer_no_account', 0, $in_accept_customer_no_account) . '&nbsp;' . $this->app->getDef('text_yes') . '&nbsp;';
        $content .= HTML::radioField('newsletters_customer_no_account', 1, $out_accept_customer_no_account) . '&nbsp;' . $this->app->getDef('text_no');
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';
        $content .= '</div>';

        $output = <<<EOD
<!-- ######################## -->
<!--  Start Newsletter no account Hooks      -->
<!-- ######################## -->
<script>
$('#newsletterFile').append(
    '{$content}'
);
</script>

<!-- ######################## -->
<!--  End Newsletter no account App      -->
<!-- ######################## -->

EOD;
        return $output;
      }
    }
  }