<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class favoriteComponents extends opFavoritePluginFavoriteComponents
{
  public function executeFavoriteListBox()
  {
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');

    $favoriteTable = Doctrine::getTable('Favorite');
    $this->cnt = $favoriteTable->countByMemberId($this->getUser()->getMemberId());
    $this->favorites = $favoriteTable->retrievesByMemberIdFrom($this->getUser()->getMemberId(), $this->row * $this->col);
    $this->members = $favoriteTable->retrieveMembers($this->favorites);
  }
}
