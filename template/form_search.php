<!-- <form action="/" method="get" class="form-search">
    <input type="hidden" name="post_type[]" value="post" />
    <input type="text" name="s" id="search" required value="<?php the_search_query(); ?>" />
    <button type="submit" class="btn-search">
        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="22" height="22" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve">
            <path d="M460.355,421.59L353.844,315.078c20.041-27.553,31.885-61.437,31.885-98.037
                C385.729,124.934,310.793,50,218.686,50C126.58,50,51.645,124.934,51.645,217.041c0,92.106,74.936,167.041,167.041,167.041
                c34.912,0,67.352-10.773,94.184-29.158L419.945,462L460.355,421.59z M100.631,217.041c0-65.096,52.959-118.056,118.055-118.056
                c65.098,0,118.057,52.959,118.057,118.056c0,65.096-52.959,118.056-118.057,118.056C153.59,335.097,100.631,282.137,100.631,217.041
                z"></path>
        </svg>
    </button>
</form> -->
?>
<form action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filter">
	<?php
		$regions = get_terms( array( 'taxonomy' => 'product_cat', 'hide_empty' => 1, 'hierarchical' => 1, ) );
		if( $regions ) {
			echo "<div class='categories'><ul>";
			foreach( $regions as $region ) {
				if( $region->parent == 0 ) {
				echo '<li><input type="checkbox" id="region_' . $region->term_id . '" name="region_' . $region->term_id . '" /><label for="region_' . $region->term_id . '">' . $region->name . '</label>';
				foreach( $regions as $subcategory ) {
					if($subcategory->parent == $region->term_id) {
						echo '<ul><li><input type="checkbox" data-parent="region_' . $region->term_id . '" id="region_' . $subcategory->term_id . '" name="region_' . $subcategory->term_id . '" /><label for="region_' . $subcategory->term_id . '">' . $subcategory->name . '</label></li></ul>';
					}
				}
				echo '</li>' . $region->term_id . '';
				
				}
			}
			echo "</ul></div>";
		}
	?>
	<?php
		if( $colors = get_terms( array( 'taxonomy' => 'product_attributes' ) ) ) {
			echo "<div class='colors'>";
			foreach( $colors as $color ) :
				$value = get_field( 'chose_color', 'term_' . $color->term_id );
				echo '<label><input type="checkbox" id="color_' . $color->term_id . '" name="color_' . $color->term_id . '" /><div class="colorbox" style="background:'. $value .'">'. $value .'</div><label for="color_' . $color->term_id . '">' . $color->name . '</label></label>';
			endforeach;
			echo "</div>";
		}
	?>
	<div class='priceFilter'>
		<input type="text" name="price_min" placeholder="Min price" />
		<input type="text" name="price_max" placeholder="Max price" />
	</div>
			
	<input type="hidden" name="action" value="myfilter">
</form>