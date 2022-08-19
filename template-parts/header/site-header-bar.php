<?php
/**
 * Displays the icsom logo as it appears on icsom.org
 *
 * @package WordPress
 * @subpackage SeznaSordino
 * @since SenzaSordino 1.0
 */

$wrapper_class = is_admin_bar_showing() ? " fix-for-wp-admin-bar" : "";

function get_image_asset($name, $height = 50) {
    $src = get_template_directory_uri() . "/assets/images/$name";
    echo "<img src=\"$src\" height=\"$height\"/>";
}

?>

<header class="global-header-wrapper<?=$wrapper_class?>" id="global-header">
    <div class="global-header-site-logo">
        <?= get_image_asset("senzaLogo.svg"); ?>
    </div>
    <div class="global-header-search">
        <span>Search</span>
    </div>
    <div class="global-header-icsom-logo">
        <?= get_image_asset("ICSOM4ColorNoText_200.png", 50); ?>
    </div>
</header>
