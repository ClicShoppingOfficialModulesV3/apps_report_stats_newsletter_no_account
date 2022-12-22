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

  use ClicShopping\OM\HTML;
  use ClicShopping\OM\CLICSHOPPING;
  use ClicShopping\OM\Registry;

  $CLICSHOPPING_Template = Registry::get('TemplateAdmin');

  $CLICSHOPPING_StatsNewsletterNoAccount = Registry::get('StatsNewsletterNoAccount');
  $CLICSHOPPING_Page = Registry::get('Site')->getPage();

  $page = (isset($_GET['page']) && is_numeric($_GET['page'])) ? (int)$_GET['page'] : 1;
?>

<div class="contentBody">
  <div class="row">
    <div class="col-md-12">
      <div class="card card-block headerCard">
        <div class="row">
          <div
            class="col-md-1 logoHeading"><?php echo HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'categories/client.gif', $CLICSHOPPING_StatsNewsletterNoAccount->getDef('heading_title'), '40', '40'); ?></div>
          <div
            class="col-md-4 pageHeading"><?php echo '&nbsp;' . $CLICSHOPPING_StatsNewsletterNoAccount->getDef('heading_title'); ?></div>
          <div class="col-md-2">
            <?php
              echo HTML::form('search', $CLICSHOPPING_StatsNewsletterNoAccount->link('StatsNewsletterNoAccount'), 'post', null, ['session_id' => true]);
              echo HTML::inputField('search', '', 'id="inputKeywords" placeholder="' . $CLICSHOPPING_StatsNewsletterNoAccount->getDef('heading_title_search') . '"');
              echo '</form>';
            ?>
          </div>
          <div class="col-md-5 text-end">
            <?php
              if (isset($_POST['search']) && !is_null($_POST['search'])) {
                echo HTML::button($CLICSHOPPING_StatsNewsletterNoAccount->getDef('button_reset'), null, $CLICSHOPPING_StatsNewsletterNoAccount->link('StatsNewsletterNoAccount'), 'warning') . ' ';
              }
              echo HTML::button($CLICSHOPPING_StatsNewsletterNoAccount->getDef('button_delete'), null, CLICSHOPPING::link('StatsNewsletterNoAccount&resetViewed=0&page=' . $page), 'danger');
            ?>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="separator"></div>
  <table border="0" width="100%" cellspacing="0" cellpadding="2">
    <td>
      <table class="table table-sm table-hover table-striped">
        <thead>
        <tr class="dataTableHeadingRow">
          <th><?php echo $CLICSHOPPING_StatsNewsletterNoAccount->getDef('table_heading_first_name'); ?></th>
          <th><?php echo $CLICSHOPPING_StatsNewsletterNoAccount->getDef('table_heading_last_name'); ?></th>
          <th
            class="text-center"><?php echo $CLICSHOPPING_StatsNewsletterNoAccount->getDef('table_heading_address_email'); ?>
            &nbsp;
          </td>
          <th
            class="text-center"><?php echo $CLICSHOPPING_StatsNewsletterNoAccount->getDef('table_heading_date_added'); ?>
            &nbsp;
          </th>
          <th class="text-end"><?php echo $CLICSHOPPING_StatsNewsletterNoAccount->getDef('table_heading_clear'); ?>
            &nbsp;
          </th>
        </tr>
        </thead>
        <tbody>
        <?php
          $rows = 0;
          // Recherche
          $search = '';
          if (isset($_POST['search']) && !is_null($_POST['search'])) {
            $keywords = HTML::sanitize($_POST['search']);

            $Qproducts = $CLICSHOPPING_StatsNewsletterNoAccount->db->prepare('select  SQL_CALC_FOUND_ROWS *
                                                                from :table_newsletters_no_account
                                                                where languages_id = :language_id
                                                                and (customers_email_address
                                                                     or  customers_lastname like :keywords
                                                                     or customers_date_added like :keywords
                                                                    )
                                                                order by customers_date_added ASC
                                                                limit :page_set_offset,
                                                                      :page_set_max_results
                                                                ');
            $Qproducts->bindValue(':keywords', $keywords);
            $Qproducts->bindInt(':language_id', $CLICSHOPPING_Language->getId());
            $Qproducts->setPageSet((int)MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
            $Qproducts->execute();
          } else {
            $Qproducts = $CLICSHOPPING_StatsNewsletterNoAccount->db->prepare('select  SQL_CALC_FOUND_ROWS *
                                                                from :table_newsletters_no_account
                                                                where languages_id = :language_id
                                                                order by customers_date_added ASC
                                                                limit :page_set_offset,
                                                                      :page_set_max_results
                                                                ');
            $Qproducts->bindInt(':language_id', $CLICSHOPPING_Language->getId());
            $Qproducts->setPageSet((int)MAX_DISPLAY_SEARCH_RESULTS_ADMIN);
            $Qproducts->execute();
          }

          $listingTotalRow = $Qproducts->getPageSetTotalRows();

          if ($listingTotalRow > 0) {

            while ($Qproducts->fetch()) {

              $rows++;

              if (strlen($rows) < 2) {
                $rows = '0' . $rows;
              }
              ?>
              <tr onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href=''">
                <th scope="row"><?php echo $Qproducts->value('customers_firstname'); ?></th>
                <td><?php echo $Qproducts->value('customers_lastname'); ?></td>
                <td><?php echo $Qproducts->value('customers_email_address'); ?></td>
                <td class="text-center"><?php echo $Qproducts->value('customers_date_added'); ?>&nbsp;</td>
                <td
                  class="text-end"><?php echo '<a href="' . $CLICSHOPPING_StatsNewsletterNoAccount->link('StatsNewsletterNoAccount&resetViewed=' . $Qproducts->value('customers_email_address') . '&page=' . $page) . '">' . HTML::image($CLICSHOPPING_Template->getImageDirectory() . 'icons/delete.gif', $CLICSHOPPING_StatsNewsletterNoAccount->getDef('image_delete')) . '</a>'; ?>
                  &nbsp;
                </td>
              </tr>
              <?php
            }
          } // end $listingTotalRow
        ?>
        </tbody>
      </table>
    </td>
  </table>
  <?php
    if ($listingTotalRow > 0) {
      ?>
      <div class="row">
        <div class="col-md-12">
          <div
            class="col-md-6 float-start pagenumber hidden-xs TextDisplayNumberOfLink"><?php echo $Qproducts->getPageSetLabel($CLICSHOPPING_StatsNewsletterNoAccount->getDef('text_display_number_of_link')); ?></div>
          <div
            class="float-end text-end"> <?php echo $Qproducts->getPageSetLinks(CLICSHOPPING::getAllGET(array('page', 'info', 'x', 'y'))); ?></div>
        </div>
      </div>
      <?php
    } // end $listingTotalRow
  ?>
</div>


