<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Lovster
 */
?>

	</div><!-- #content -->

	<footer id="colophon" class="site-footer" role="contentinfo">
		<div class="site-info">
			<a href="<?php echo esc_url( __( 'http://wordpress.org/', 'lovster' ) ); ?>"><?php printf( __( 'Proudly powered by %s', 'lovster' ), 'WordPress' ); ?></a>
			<span class="sep"> | </span>
			<?php printf( __( 'Theme: %1$s by %2$s.', 'lovster' ), 'Lovster', '<a href="http://bueninvento.es" rel="designer">Fernando Guirao</a>' ); ?>
		</div><!-- .site-info -->
	</footer><!-- #colophon -->
</div><!-- #page -->

<?php wp_footer(); ?>

  <script type='text/javascript'>//<![CDATA[
  document.write("<script async src='//HOST:3000/browser-sync-client.1.3.7.js'><\/script>".replace(/HOST/g, location.hostname));
  //]]></script>

</body>
</html>
