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
?>
<div class="contentText">
  <div class="modulesAccountCustomersNewsletterNoAccountSuccessText"><?php echo CLICSHOPPING::getDef('text_success Congratulation', ['store_name' => STORE_NAME]); ?></div>
  <div class="separator"></div>
  <div class="control-group">
    <div class="controls">
      <div class="buttonSet">
        <span class="float-end"><?php echo $button_process; ?></span>
      </div>
    </div>
  </div>
</div>