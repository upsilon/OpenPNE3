<p>
<?php echo link_to(__('Privacy policy'), '@privacy_policy', array('target' => '_blank')); ?> 
<?php echo link_to(__('Terms of service'), '@user_agreement', array('target' => '_blank')); ?> 
<?php $snsConfigSettings = sfConfig::get('openpne_sns_config'); ?>
<?php if (opToolkit::isSecurePage()) : ?>
<?php echo opConfig::get('footer_after', $snsConfigSettings['footer_after']['Default']); ?>
<?php else: ?>
<?php echo opConfig::get('footer_before', $snsConfigSettings['footer_before']['Default']); ?>
<?php endif; ?>
</p>
