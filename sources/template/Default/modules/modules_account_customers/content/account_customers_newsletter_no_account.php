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

  echo $form
?>
<div class="col-md-<?php echo $content_width; ?>">
  <div class="separator"></div>
  <div>
    <span class="text-warning" style="float: right;"><?php echo CLICSHOPPING::getDef('form_required'); ?></span>
    <span class="page-header modulesAccountCustomersNewsletterNoAccountCategoryPersonnal"><h3><?php echo CLICSHOPPING::getDef('text_category_personal'); ?></legend></h3></span>
  </div>

  <div><?php echo CLICSHOPPING::getDef('text_origin_login', ['store_name' => STORE_NAME]); ?></div>

  <div class="separator"></div>
  <div class="input-group col-md-12">
    <label for="InputFirstName" class="col-md-2 sr-only"><?php echo CLICSHOPPING::getDef('entry_first_name'); ?></label>
    <span class="col-md-5 text-start input-group-addon" id="InputFirstName"><?php echo CLICSHOPPING::getDef('entry_first_name'); ?></span>
    <?php echo HTML::inputField('firstname', null, 'required aria-required="true" id="InputFirstName" aria-describedby="' . CLICSHOPPING::getDef('entry_first_name') . '" placeholder="' . CLICSHOPPING::getDef('entry_first_name') . '" minlength="'. ENTRY_FIRST_NAME_MIN_LENGTH .'"'); ?>
  </div>
  <div class="separator"></div>
  <div class="input-group col-md-12">
    <label for="InputLastName" class="col-md-2 sr-only"><?php echo CLICSHOPPING::getDef('entry_last_name_text'); ?></label>
    <span class="col-md-5 text-start input-group-addon" id="InputLastName"><?php echo CLICSHOPPING::getDef('entry_last_name'); ?></span>
    <?php echo HTML::inputField('lastname', null, 'required aria-required="true" id="InputLastName" aria-describedby="' . CLICSHOPPING::getDef('entry_last_name') . '" placeholder="' . CLICSHOPPING::getDef('entry_last_name') . '" minlength="'. ENTRY_LAST_NAME_MIN_LENGTH .'"'); ?>
  </div>
  <div class="separator"></div>
  <div class="input-group col-md-12">
    <label for="InputEmail" class="col-md-2 sr-only"><?php echo CLICSHOPPING::getDef('entry_email_address'); ?></label>
    <span class="col-md-5 text-start input-group-addon" id="InputEmail"><?php echo CLICSHOPPING::getDef('entry_email_address'); ?></span>
    <?php echo HTML::inputField('email_address', null, 'required aria-required="true" id="InputEmail" aria-describedby="' . CLICSHOPPING::getDef('entry_email_address') . '" placeholder="' . CLICSHOPPING::getDef('entry_email_address') . '"', 'email'); ?>
  </div>

  <div class="separator"></div>
  <div class="input-group col-md-12">
    <label for="inputVerificationCode" class="col-md-2 sr-only"><?php echo CLICSHOPPING::getDef('entry_email_address_confirmation'); ?></label>
    <span class="col-md-5 text-start input-group-addon" id="inputVerificationCode"><?php echo CLICSHOPPING::getDef('entry_email_address_confirmation'); ?></span>
    <?php echo HTML::inputField('email_address', null, 'required aria-required="true" id="inputVerificationCode" aria-describedby="' . CLICSHOPPING::getDef('entry_email_address_confirmation') . '" placeholder="' . CLICSHOPPING::getDef('entry_email_address_confirmation') . '"', 'email'); ?>
  </div>
</div>
