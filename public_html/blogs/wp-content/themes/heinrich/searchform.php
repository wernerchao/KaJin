<?php
/**
 * A Custom Search Form
 * @package Heinrich
 * since heinrich 1.0
 */

?>

<form role="search" method="get" class="searchform" action="<?php echo home_url( '/' ); ?>">
		<label for="s" class="screen-reader-text">Search for &hellip;</label>
		<input id="s" type="search" class="search-field s" placeholder="<?php _e( 'Search for &hellip;', 'heinrich' ); ?>" value="" name="s" />
		<input type="submit" class="search-submit" value="" />
</form>