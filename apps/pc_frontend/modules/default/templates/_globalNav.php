<?php if ($navs): ?>
<ul>
<?php foreach ($navs as $nav): ?>
<?php if (op_is_accessable_url($nav->getUri())): ?>
<li id="globalNav_<?php echo op_url_to_id($nav->getUri()) ?>"><?php echo link_to($nav->getCaption(), $nav->getUri()) ?></li>
<?php endif; ?>
<?php endforeach; ?>
</ul>
<?php endif; ?>
