<div class="multiline newline">
	<span><?php wptouch_admin_the_setting_desc(); ?></span>
	<?php if ( wptouch_admin_setting_has_tooltip() ) { ?>
		<i class="wptouch-tooltip" title="<?php wptouch_admin_the_setting_tooltip(); ?>"></i>
	<?php } ?>
	<?php if ( wptouch_admin_is_setting_new() ) { ?>
		<span class="new">&nbsp;<?php _e( 'New', 'wptouch-pro' ); ?></span>
	<?php } ?>

	<div class="input-wrap">
		<input type="text" class="add-entry" placeholder="" /><a href="#" class="add icon-plus-circled"></a>
		<textarea style="width:400px; height: 100px" id="<?php wptouch_admin_the_setting_name(); ?>" name="<?php wptouch_admin_the_encoded_setting_name(); ?>"><?php wptouch_admin_the_setting_value(); ?></textarea>
	</div>

	<?php $entries = wptouch_admin_split_setting_newline(); ?>

	<?php if ( count( $entries ) > 0 ) { ?>
		<ul class="<?php wptouch_admin_the_encoded_setting_name(); ?>-list">
		<?php foreach ( $entries as $entry ) { ?>
			<?php if ( $entry != '' ) { ?>
				<li><?php echo $entry; ?></li>
			<?php } ?>
		<?php } ?>
		</ul>
	<?php } ?>
</div>