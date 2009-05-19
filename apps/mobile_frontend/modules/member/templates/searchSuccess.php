<?php op_mobile_page_title(__('Search Members')) ?>

<?php if ($pager->getNbResults()): ?>
<center>
<?php op_include_pager_total($pager); ?>
</center>
<?php
$list = array();
foreach ($pager->getResults() as $member)
{
  $list[] = link_to(sprintf('%s', $member->getName()), 'member/profile?id=' . $member->getId());
}
$option = array(
  'border' => true,
);
op_include_list('memberList', $list, $option);
?>
<?php else: ?>
<?php echo __('Your search queries did not match any members.') ?>
<?php endif ?>

<?php
$options = array(
  'url'    => url_for('member/search'),
  'button' => __('Search'),
);
?>

<table width="100%">
<tbody><tr><td bgcolor="#7ddadf">
<font color="#000000">
<?php echo __('Search Members') ?>
</font><br/>
</td></tr>
</tbody></table>

<?php op_include_form('searchMember', $filters, $options); ?>
