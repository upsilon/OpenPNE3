<?php if ($navs): ?>
<ul class="<?php echo $type; ?>">
<?php foreach ($navs as $nav): ?>

<?php if (isset($navId)): ?>
<?php $uri = $nav->getUri().'?id='.$navId; ?>
<?php else: ?>
<?php $uri = $nav->getUri(); ?>
<?php endif; ?>

<?php if (op_is_accessible_url($uri)): ?>
<li id="<?php echo $nav->getType() ?>_<?php echo op_url_to_id($nav->getUri()) ?>"><?php echo link_to($nav->getCaption(), $uri); ?></li>
<?php endif; ?>

<?php endforeach; ?>
</ul>
<?php endif; ?>
