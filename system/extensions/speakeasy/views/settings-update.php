<fieldset>
  <h2><?php echo $vars['lang']->line('updates_title'); ?></h2>
  <div class="info">
    <?php echo $vars['lang']->line('updates_info'); ?>
  </div>
  
  <table cellpadding="0" cellspacing="0">
    <tbody>
      <tr class="odd">
        <th><label for="update_check"><?php echo $vars['lang']->line('updates_label'); ?></label></th>
        <td>
          <label>
            <input id="update_check_yes" name="update_check" type="radio" value="y" <?php if ($vars['settings']['update_check'] === 'y') echo 'checked="checked"'; ?> />
            <?php echo $vars['lang']->line('updates_yes'); ?>
          </label>
          
          <label>
            <input id="update_check_no" name="update_check" type="radio" value="n" <?php if ($vars['settings']['update_check'] !== 'y') echo 'checked="checked"'; ?> />
            <?php echo $vars['lang']->line('updates_no'); ?>
          </label>
        </td>
      </tr>
    </tbody>
  </table>
</fieldset>