/* TensorCore performance: inline the theme stylesheet to remove the render-blocking CSS request. Disable this snippet to undo. */
add_filter( 'style_loader_tag', function ( $tag, $handle ) {
	if ( 'extendable-style' !== $handle || is_admin() ) {
		return $tag;
	}
	$file = get_template_directory() . '/style.css';
	if ( ! is_readable( $file ) ) {
		return $tag;
	}
	$key = 'tc_inline_css_' . filemtime( $file );
	$css = get_transient( $key );
	if ( false === $css ) {
		$css = file_get_contents( $file );
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = preg_replace( '/\s+/', ' ', $css );
		$css = str_replace( array( ' {', '{ ', ' }', '} ', '; ', ': ', ', ' ), array( '{', '{', '}', '}', ';', ':', ',' ), $css );
		$css = trim( $css );
		set_transient( $key, $css, WEEK_IN_SECONDS );
	}
	if ( strlen( $css ) < 1000 ) {
		return $tag;
	}
	return '<style id="extendable-style-inline">' . $css . '</style>' . "\n";
}, 10, 2 );
