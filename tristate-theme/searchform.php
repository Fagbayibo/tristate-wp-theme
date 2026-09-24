<?php
/**
 * Search form.
 *
 * @package tristate
 */
?>
<form class="search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label class="screen-reader-text" for="search-field"><?php esc_html_e( 'Search the site', 'tristate' ); ?></label>
	<input class="field__control" type="search" id="search-field" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php esc_attr_e( 'Search news, services…', 'tristate' ); ?>">
	<button class="btn btn--brand" type="submit"><?php esc_html_e( 'Search', 'tristate' ); ?></button>
</form>
