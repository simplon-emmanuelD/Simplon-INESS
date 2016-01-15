<?php
	if(!defined('xmt'))
		exit;
		
	$arr_scr_dir = array(
		'up' => 'Up',
		'down' => 'Down',
	);
?>
<table cellpadding="0" cellspacing="0">
	<tr>
		<td width="150px"><?php echo __('Area Height', 'xmt'); ?></td>
		<td width="200px"><input type="text" id="int_xmt_thm_scr_szh" name="int_xmt_thm_scr_szh" value="<?php echo $cfg['thm_scr_szh']; ?>" size="5"  maxlength="5"/> px</td>
		<td width="10px"></td>
		<td width="150px"><?php echo __('Animate Scrolling?', 'xmt'); ?></td>
		<td width="200px"><input type="checkbox" id="chk_xmt_thm_scr_anm" name="chk_xmt_thm_scr_anm" value="1" <?php echo ($cfg['thm_scr_anm']?'checked="checked"':''); ?>/></td>
	</tr>
	<tr>
		<td><?php echo __('Scroll Amount', 'xmt'); ?></td>
		<td><input type="text" id="int_xmt_thm_scr_anm_amt" name="int_xmt_thm_scr_anm_amt" value="<?php echo $cfg['thm_scr_anm_amt']; ?>" size="5"  maxlength="5"/> px</td>
		<td width="10px"></td>
		<td><?php echo __('Scroll Delay', 'xmt'); ?></td>
		<td><input type="text" id="int_xmt_thm_scr_anm_dly" name="int_xmt_thm_scr_anm_dly" value="<?php echo $cfg['thm_scr_anm_dly']; ?>" size="5"  maxlength="5"/> ms</td>
	</tr>
	<tr>
		<td><?php echo __('Direction', 'xmt'); ?></td>
		<td>
			<select id="cbo_xmt_thm_scr_anm_dir" name="cbo_xmt_thm_scr_anm_dir" style="width:100%" onchange="show_mode_opt()">															
			<?php foreach($arr_scr_dir as $key=>$val){ ?>
				<option value="<?php echo $key; ?>" <?php echo ($cfg['thm_scr_anm_dir']==$key)?'selected="selected"':''; ?>><?php echo __(ucwords($val), 'xmt'); ?></option>									
			<?php } ?>
			</select>
		</td>
		<td width="10px"></td>
		<td></td>
		<td></td>
	</tr>
</table>