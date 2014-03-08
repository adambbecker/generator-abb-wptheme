<?php
/**
 * Searchform Template
 *
 * Acts as the template for the search form widget (or anywhere get_search_form() is called).
 *
 * @package WordPress
 * @subpackage <%= themeTitle %> theme
 * @since 1.0.0
 */
 
?>

<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ); ?>" class="search-form">
    <input type="text" value="" name="s" id="s" placeholder="Enter search, press enter" />
</form>