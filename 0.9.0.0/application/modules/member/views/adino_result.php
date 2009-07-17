<h1 ><?=__("Lost password", $this->template['module'])?></h1>
<p>
<?php echo $message ?>
</p>
<p>
<a href="<?php echo site_url( $this->session->userdata("last_uri") ) ?>"><?php _e("Go back", $this->template['module']) ?></a>
</p>

