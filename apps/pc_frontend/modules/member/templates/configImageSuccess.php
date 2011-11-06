<?php op_include_parts('memberImagesBox', 'memberImageUploadBox', array(
  'title'  => __('Edit Photo'),
  'images' => $sf_user->getMember()->getMemberImage(),
  'form'   => $form,
)); ?>

<?php
if (Doctrine::getTable('SnsConfig')->get('enable_gravatar'))
{
  slot('gravatar_config');
  echo __('If you would like to use Gravatar as your icon, ');
  echo link_to(__('turn on Gravatar in settings.'), '@member_config?category=gravatar');
  end_slot();

  op_include_box('memberGravatarConfig', get_slot('gravatar_config'), array(
    'title' => 'Gravatar',
  ));
}
?>
