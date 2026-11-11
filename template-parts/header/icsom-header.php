<?php
/**
 * Displays the ICSOM site header (navigation bar, logo and account buttons) as on icsom.org.
 *
 * @package WordPress
 * @subpackage senzasordino
 * @since senzasordino 1.2
 */

?>
<div class="icsom-header">
	<nav class="navbar navbar-expand-lg navbar-dark icsom-navbar" aria-label="<?php esc_attr_e( 'ICSOM', 'senzasordino' ); ?>">
		<div class="container-fluid">
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#icsom-nav" aria-controls="icsom-nav" aria-expanded="false" aria-label="<?php esc_attr_e( 'Toggle navigation', 'senzasordino' ); ?>">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="icsom-nav">
				<?php senzasordino_icsom_nav(); ?>
			</div>
		</div>
	</nav>

	<div class="icsom-brand">
		<div class="container icsom-brand-inner">
			<a class="icsom-logo" href="https://www.icsom.org/">
				<img class="icsom-logo-horizontal" src="<?php echo esc_url( senzasordino_image_uri( 'ICSOM_color_Horiz.png' ) ); ?>" width="1219" height="199" alt="<?php esc_attr_e( 'ICSOM: International Conference of Symphony and Opera Musicians', 'senzasordino' ); ?>" />
				<img class="icsom-logo-vertical" src="<?php echo esc_url( senzasordino_image_uri( 'ICSOM_mobile_logo3.png' ) ); ?>" width="400" height="264" alt="<?php esc_attr_e( 'ICSOM', 'senzasordino' ); ?>" />
			</a>
			<div class="icsom-account">
				<a class="btn icsom-button" href="https://www.icsom.org/access/"><?php esc_html_e( 'Log in', 'senzasordino' ); ?></a>
				<a class="btn icsom-button" href="https://www.icsom.org/contact/register.php"><?php esc_html_e( 'Register', 'senzasordino' ); ?></a>
			</div>
		</div>
	</div>
</div>
