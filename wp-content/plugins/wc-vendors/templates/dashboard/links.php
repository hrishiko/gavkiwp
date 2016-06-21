<center>
<p>
<font size="3">
        <u><a href="<?php echo $shop_page; ?>" class="button" style="color: green"><?php echo _e( 'View Your Store', 'wcvendors' ); ?></a></u>&nbsp; &nbsp; |  &nbsp;&nbsp;
        <u><a href="<?php echo $settings_page; ?>" class="button" style="color: green"><?php echo _e( 'Store Settings', 'wcvendors' ); ?></a></u>&nbsp; &nbsp; |  &nbsp;&nbsp;

<?php if ( $can_submit ) { ?>
                <u><a target="_TOP" href="<?php echo $submit_link; ?>" class="button" style="color: green"><?php echo _e( 'Add New Product', 'wcvendors' ); ?></a></u>&nbsp; &nbsp; |  &nbsp;&nbsp;
                <u><a target="_TOP" href="<?php echo $edit_link; ?>" class="button" style="color: green"><?php echo _e( 'Edit Products', 'wcvendors' ); ?></a></u>
<?php } ?>
<font>
</center>

<hr>