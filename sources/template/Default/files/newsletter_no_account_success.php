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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;

 require_once($CLICSHOPPING_Template->getTemplateFiles('breadcrumb'));
?>
<section class="newsletter_no_account_success" id="newsletter_no_account_success">
  <div class="contentContainer">
    <div class="contentText">
      <div class="page-header modulesAccountCustomersNewsletterNoAccountSuccessPageHeader"><h1><?php echo HEADING_TITLE; ?></h1></div>
    </div>
    <?php echo $CLICSHOPPING_Template->getBlocks('modules_account_customers'); ?>
  </div>
</section>

