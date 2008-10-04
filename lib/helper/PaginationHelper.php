<?php

/**
 * PaginationHelper.
 *
 * @package    openpne
 * @subpackage helper
 * @author     Kousuke Ebihara <ebihara@tejimaya.com>
 */

/**
 * Returns a navigation for paginated list.
 *
 * @param  sfPager $pager
 * @param  string  $link_to  A path to go to next/previous page.
                             "%d" will be converted to number of page.
 * @return string  A navigation for paginated list.
 */
function pager_navigation($pager, $link_to)
{
  $navigation = '';

  if ($pager->haveToPaginate()) {
    if ($pager->getPreviousPage() != $pager->getPage()) {
      $navigation .= link_to('&lt;前', sprintf($link_to, $pager->getPreviousPage())) . '&nbsp;';
    }
  }

  $navigation .= sprintf('%d件〜%d件を表示', $pager->getFirstIndice(), $pager->getLastIndice());

  if ($pager->haveToPaginate()) {
    if ($pager->getNextPage() != $pager->getPage()) {
      $navigation .= '&nbsp;' . link_to('次&gt;', sprintf($link_to, $pager->getNextPage()));
    }
  }

  return $navigation;
}
