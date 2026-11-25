/* TensorCore performance 2: inline the small theme "inverse" stylesheet and underline links in page text (homepage and pages). Disable to undo. */
add_filter( 'style_loader_tag', function ( $tag, $handle, $href ) {
	if ( 'extendable-inverse-style' !== $handle || is_admin() ) {
		return $tag;
	}
	$url = strtok( $href, '?' );
	if ( 0 === strpos( $url, '//' ) ) {
		$url = 'https:' . $url;
	}
	$path = str_replace( array( content_url(), includes_url() ), array( WP_CONTENT_DIR, ABSPATH . WPINC . '/' ), $url );
	if ( ! is_readable( $path ) ) {
		return $tag;
	}
	$key = 'tc_inline_inv_' . md5( $path . filemtime( $path ) );
	$css = get_transient( $key );
	if ( false === $css ) {
		$css = file_get_contents( $path );
		if ( false !== strpos( $css, 'url(' ) || false !== strpos( $css, '@import' ) ) {
			return $tag; // keep external if it references other files
		}
		$css = preg_replace( '#/\*.*?\*/#s', '', $css );
		$css = trim( preg_replace( '/\s+/', ' ', $css ) );
		set_transient( $key, $css, WEEK_IN_SECONDS );
	}
	if ( strlen( $css ) < 100 ) {
		return $tag;
	}
	return '<style id="extendable-inverse-inline">' . $css . '</style>' . "\n";
}, 10, 3 );
add_action( 'wp_head', function () {
	echo '<style id="tc-a11y-links-2">main p a:not([class]){text-decoration:underline;text-decoration-thickness:1px;text-underline-offset:3px}</style>' . "\n";
}, 99 );
