/* TensorCore performance: swap font display and underline links in article text. */
add_filter( 'wp_theme_json_data_theme', 'tc_font_swap', 99 );
add_filter( 'wp_theme_json_data_user', 'tc_font_swap', 99 );
function tc_font_swap( $theme_json ) {
	$data = $theme_json->get_data();
	if ( empty( $data['settings']['typography']['fontFamilies'] ) || ! is_array( $data['settings']['typography']['fontFamilies'] ) ) {
		return $theme_json;
	}
	$fams = $data['settings']['typography']['fontFamilies'];
	// Raw data may be a plain list or keyed by origin (theme/custom/default).
	$lists = isset( $fams[0] ) ? array( 'list' => $fams ) : $fams;
	$out   = array();
	$changed = false;
	foreach ( $lists as $key => $list ) {
		if ( ! is_array( $list ) ) {
			continue;
		}
		foreach ( $list as $i => $fam ) {
			if ( ! empty( $fam['fontFace'] ) && is_array( $fam['fontFace'] ) ) {
				foreach ( $fam['fontFace'] as $j => $face ) {
					if ( ! isset( $face['fontDisplay'] ) || 'swap' !== $face['fontDisplay'] ) {
						$list[ $i ]['fontFace'][ $j ]['fontDisplay'] = 'swap';
						$changed = true;
					}
				}
			}
		}
		$out = array_merge( $out, $list );
	}
	if ( ! $changed ) {
		return $theme_json;
	}
	return $theme_json->update_with( array(
		'version'  => 3,
		'settings' => array( 'typography' => array( 'fontFamilies' => $out ) ),
	) );
}
add_action( 'wp_head', function () {
	echo '<style id="tc-a11y-links">.wp-block-post-content :is(p,li,td,figcaption) a:not([class]){text-decoration:underline;text-decoration-thickness:1px;text-underline-offset:3px}.wp-block-post-content :is(p,li,td,figcaption) a:not([class]):hover{text-decoration-thickness:2px}</style>' . "\n";
}, 99 );
