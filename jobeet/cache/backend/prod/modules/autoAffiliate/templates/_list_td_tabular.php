<td class="sf_admin_text sf_admin_list_td_id">
  <?php echo link_to($jobeet_affiliate->getId(), 'jobeet_affiliate_edit', $jobeet_affiliate) ?>
</td>
<td class="sf_admin_text sf_admin_list_td_url">
  <?php echo $jobeet_affiliate->getUrl() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_email">
  <?php echo $jobeet_affiliate->getEmail() ?>
</td>
<td class="sf_admin_text sf_admin_list_td_token">
  <?php echo $jobeet_affiliate->getToken() ?>
</td>
<td class="sf_admin_boolean sf_admin_list_td_is_active">
  <?php echo get_partial('affiliate/list_field_boolean', array('value' => $jobeet_affiliate->getIsActive())) ?>
</td>
<td class="sf_admin_date sf_admin_list_td_created_at">
  <?php echo false !== strtotime($jobeet_affiliate->getCreatedAt()) ? format_date($jobeet_affiliate->getCreatedAt(), "f") : '&nbsp;' ?>
</td>
