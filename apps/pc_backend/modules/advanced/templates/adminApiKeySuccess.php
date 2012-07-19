<?php slot('submenu') ?>
<?php include_partial('submenu'); ?>
<?php end_slot() ?>

<h2><?php echo __('Admin API Key Configuration') ?></h2>

<h3><?php echo __('Admin API Key') ?></h3>

<?php if ($apiKey): ?>
<div class="apiKey"><?php echo $apiKey ?></div>
<p>
<?php echo link_to(__('Reset Admin API Key'), 'advanced/adminApiKey?reset_api_key=1', array('method' => 'post')) ?>
</p>
<?php else: ?>
<p><?php echo __('Admin API Key not yet generated.') ?></p>
<p>
<?php echo link_to(__('Generate Admin API Key'), 'advanced/adminApiKey?reset_api_key=1', array('method' => 'post')) ?>
</p>
<?php endif ?>
