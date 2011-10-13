<?php

/**
 * This file is part of the OpenPNE package.
 * (c) OpenPNE Project (http://www.openpne.jp/)
 *
 * For the full copyright and license information, please view the LICENSE
 * file and the NOTICE file that were distributed with this source code.
 */

class communityComponents extends opCommunityComponents
{
  public function executeJoinListBox($request)
  {
    if ($request->hasParameter('id') && $request->getParameter('module') == 'member' && $request->getParameter('action') == 'profile')
    {
      $this->member = Doctrine::getTable('Member')->find($request->getParameter('id'));
    }
    else
    {
      $this->member = $this->getUser()->getMember();
    }
    $this->row = $this->gadget->getConfig('row');
    $this->col = $this->gadget->getConfig('col');
    $this->crownIds = $this->getEntityManager()->getRepository('CommunityMember')->getCommunityIdsOfAdminByMemberId($this->member->getId());
    $this->communities = $this->getEntityManager()->getRepository('Community')->retrievesByMemberId($this->member->getId(), $this->row * $this->col, true);
  }
}
