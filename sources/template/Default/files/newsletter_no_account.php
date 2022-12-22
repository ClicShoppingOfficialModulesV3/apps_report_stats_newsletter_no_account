<?php
/**
 *
 *  @copyright 2008 - https://www.clicshopping.org
 *  @Brand : ClicShopping(Tm) at Inpi all right Reserved
 *  @Licence GPL 2 & MIT
 *  @licence MIT - Portion of osCommerce 2.4
 *  @Info : https://www.clicshopping.org/forum/trademark/
 *
 */

  use ClicShopping\OM\CLICSHOPPING;

 require_once($CLICSHOPPING_Template->getTemplateFiles('breadcrumb'));

  if ( $CLICSHOPPING_MessageStack->exists('newsletter_no_account')) {
    echo $CLICSHOPPING_MessageStack->get('newsletter_no_account');
  }
?>
<section class="newsletter_no_account" id="newsletter_no_account">
  <div class="contentContainer">
    <div class="contentText">
      <div class="page-header modulesAccountCustomersNewsletterNoAccountPageHeader"><h1><?php echo CLICSHOPPING::getDef('heading_title_Newsletter'); ?></h1></div>
      <?php echo $CLICSHOPPING_Template->getBlocks('modules_account_customers'); ?>
    </div>
  </div>
</section>
