<?php
/**
 * Search and sharing: page titles, meta descriptions, social previews (Open
 * Graph), structured data for Google, and sitemap/robots clean-up.
 *
 * The site has no SEO plugin. If one is installed later (Yoast, Rank Math, …)
 * the theme leaves titles, descriptions, social tags and breadcrumbs to it and
 * keeps only what plugins don't cover: the two hospitals' structured data and
 * the sitemap and robots rules below.
 *
 * @package tristate
 */

defined( 'ABSPATH' ) || exit;

/** Is an SEO plugin handling titles and meta tags? */
function tristate_seo_plugin_active() {
	return defined( 'WPSEO_VERSION' ) || defined( 'RANK_MATH_VERSION' ) || defined( 'AIOSEO_VERSION' )
		|| defined( 'SEOPRESS_VERSION' ) || defined( 'THE_SEO_FRAMEWORK_VERSION' );
}

/** Plain text cut to about $max characters at a word boundary. */
function tristate_seo_trim( $text, $max = 155 ) {
	$text = trim( preg_replace( '/\s+/', ' ', wp_strip_all_tags( (string) $text ) ) );
	if ( mb_strlen( $text ) <= $max ) {
		return $text;
	}
	$cut = mb_substr( $text, 0, $max );
	return rtrim( mb_substr( $cut, 0, (int) mb_strrpos( $cut, ' ' ) ), ' ,.;:' ) . '…';
}

/** A theme photo as [ url, width, height ]. */
function tristate_seo_theme_image( $file ) {
	$path = TRISTATE_DIR . '/assets/images/' . $file;
	$size = is_readable( $path ) ? getimagesize( $path ) : false;
	return array( TRISTATE_URI . '/assets/images/' . $file, $size ? $size[0] : 0, $size ? $size[1] : 0 );
}

/**
 * Title, description and share image for the page being viewed.
 * Descriptions use the client's own wording wherever the brief has it.
 *
 * @return array { title, description, image: [ url, w, h ], type }
 */
function tristate_seo() {
	static $seo = null;
	if ( null !== $seo ) {
		return $seo;
	}

	$brand = 'Tristate Hospitals';
	$seo   = array(
		'title'       => $brand,
		'description' => 'Multispecialty care in Lekki and Ibadan: medical and surgical care around your needs, with advanced cardiovascular care as our signature programme.',
		'image'       => tristate_seo_theme_image( 'hero-home.jpg' ),
		'type'        => 'website',
	);

	$pages = array(
		'our-services'  => array( 'Our Services', 'Find care for what matters to you: medical care, heart and chest care, surgery, women’s health, emergency care, diagnostics and more in Lekki and Ibadan.', 'service-outpatient.jpg' ),
		'our-team'      => array( 'Our Team', 'Get to know your care team. Learn about our clinicians’ experience and areas of practice, and find out where they consult.', 'hero-home.jpg' ),
		'patient-guide' => array( 'Patient Guide', 'Make your visit work for you: what to bring, what to ask during your consultation and what to confirm before you leave.', 'hero-care.jpg' ),
		'hmo-partners'  => array( 'HMO Partners', 'Accepted partners include Leadway, AXA Mansard and Alliance. We can help check eligibility, referral requirements, prior approval and costs before your visit.', 'appointment.jpg' ),
		'our-hospitals' => array( 'Our Hospitals in Lekki and Ibadan', 'Find Tristate Hospitals in Lekki, Lagos, and Idi-Ape, Ibadan: addresses, directions, contact details and the emergency line.', 'hero-home.jpg' ),
		'about-us'      => array( 'About Us', 'Tristate Hospitals is part of Tristate Healthcare System, with hospitals in Lekki and Ibadan. Advanced cardiovascular care is our signature programme.', 'hero-home.jpg' ),
	);

	$slug    = tristate_current_slug();
	$service = $slug ? tristate_service( $slug ) : null;

	if ( is_front_page() ) {
		$seo['title'] = $brand . ' | Multispecialty Care in Lekki and Ibadan';
	} elseif ( $service ) {
		$name                = 'cardiovascular-services' === $slug ? 'Cardiology and Advanced Cardiovascular Services' : $service['title'];
		$seo['title']        = $name . ' | ' . $brand . ', Lekki and Ibadan';
		$seo['description']  = tristate_seo_trim( implode( ' ', $service['intro'] ) );
		$seo['image']        = tristate_seo_theme_image( $service['image'] );
	} elseif ( isset( $pages[ $slug ] ) ) {
		$seo['title']       = $pages[ $slug ][0] . ' | ' . $brand;
		$seo['description'] = $pages[ $slug ][1];
		$seo['image']       = tristate_seo_theme_image( $pages[ $slug ][2] );
	} elseif ( is_home() ) {
		$seo['title']       = 'Health insights and hospital news | ' . $brand;
		$seo['description'] = 'Health insights from our specialists and news from Tristate Hospitals in Lekki and Ibadan.';
	} elseif ( is_singular() ) {
		$post               = get_queried_object();
		$seo['title']       = single_post_title( '', false ) . ' | ' . $brand;
		$seo['description'] = tristate_seo_trim( has_excerpt( $post ) ? $post->post_excerpt : $post->post_content );
		$seo['type']        = is_singular( 'post' ) ? 'article' : 'website';
		$thumb              = has_post_thumbnail( $post ) ? wp_get_attachment_image_src( get_post_thumbnail_id( $post ), 'large' ) : false;
		if ( $thumb ) {
			$seo['image'] = array( $thumb[0], $thumb[1], $thumb[2] );
		}
	} elseif ( is_category() || is_tag() ) {
		$seo['title']       = single_term_title( '', false ) . ' | ' . $brand;
		$seo['description'] = tristate_seo_trim( term_description() ? term_description() : $seo['description'] );
	} elseif ( is_search() ) {
		$seo['title'] = sprintf( 'Search results for “%s” | %s', get_search_query(), $brand );
	} elseif ( is_404() ) {
		$seo['title'] = 'Page not found | ' . $brand;
	}

	return $seo;
}

/* Titles, descriptions and social previews (only without an SEO plugin) --------- */

add_filter( 'pre_get_document_title', function ( $title ) {
	return tristate_seo_plugin_active() ? $title : tristate_seo()['title'];
}, 20 );

add_action( 'wp_head', function () {
	if ( tristate_seo_plugin_active() ) {
		return;
	}
	$seo = tristate_seo();
	$url = is_singular() && ! is_front_page() ? get_permalink() : home_url( user_trailingslashit( $GLOBALS['wp']->request ) );

	$tags = array(
		array( 'name', 'description', $seo['description'] ),
		array( 'property', 'og:type', $seo['type'] ),
		array( 'property', 'og:site_name', 'Tristate Hospitals' ),
		array( 'property', 'og:locale', 'en_NG' ),
		array( 'property', 'og:title', $seo['title'] ),
		array( 'property', 'og:description', $seo['description'] ),
		array( 'property', 'og:url', $url ),
		array( 'property', 'og:image', $seo['image'][0] ),
		array( 'property', 'og:image:width', $seo['image'][1] ),
		array( 'property', 'og:image:height', $seo['image'][2] ),
		array( 'name', 'twitter:card', 'summary_large_image' ),
		array( 'name', 'twitter:title', $seo['title'] ),
		array( 'name', 'twitter:description', $seo['description'] ),
		array( 'name', 'twitter:image', $seo['image'][0] ),
	);
	foreach ( $tags as $tag ) {
		if ( '' !== (string) $tag[2] && 0 !== $tag[2] ) {
			printf( '<meta %s="%s" content="%s">' . "\n", esc_attr( $tag[0] ), esc_attr( $tag[1] ), esc_attr( $tag[2] ) );
		}
	}
	if ( is_singular( 'post' ) ) {
		printf( '<meta property="article:published_time" content="%s">' . "\n", esc_attr( get_the_date( 'c' ) ) );
	}
	// WordPress adds a canonical link to single pages only; the news page needs one too.
	if ( is_home() && ! is_paged() && get_option( 'page_for_posts' ) ) {
		printf( '<link rel="canonical" href="%s">' . "\n", esc_url( get_permalink( get_option( 'page_for_posts' ) ) ) );
	}
}, 2 );

/* Structured data (JSON-LD) ---------------------------------------------------- */

/** The organisation and its two hospitals, for Google's local results. */
function tristate_schema_organisation() {
	$org = array(
		'@type'         => 'MedicalOrganization',
		'@id'           => home_url( '/#organization' ),
		'name'          => 'Tristate Healthcare System',
		'alternateName' => 'Tristate Hospitals',
		'url'           => home_url( '/' ),
		'logo'          => TRISTATE_URI . '/assets/images/logo-white.png',
		'email'         => tristate_contact( 'email' ),
		'telephone'     => tristate_contact( 'emergency_tel' ),
		'sameAs'        => array_values( wp_list_pluck( tristate_socials(), 1 ) ),
	);

	$graph = array( $org );
	foreach ( tristate_locations() as $key => $place ) {
		$address = array_filter( array(
			'@type'           => 'PostalAddress',
			'streetAddress'   => $place['street'],
			'addressLocality' => $place['locality'],
			'addressRegion'   => $place['region'],
			'postalCode'      => $place['postcode'],
			'addressCountry'  => 'NG',
		) );
		$graph[] = array(
			'@type'              => 'Hospital',
			'@id'                => tristate_page_url( 'our-hospitals', $key ),
			'name'               => $place['name'],
			'url'                => tristate_page_url( 'our-hospitals', $key ),
			'telephone'          => tristate_contact( 'emergency_tel' ),
			'email'              => tristate_contact( 'email' ),
			'image'              => TRISTATE_URI . '/assets/images/hero-home.jpg',
			'address'            => $address,
			'hasMap'             => tristate_directions_url( $place['address'] ),
			'parentOrganization' => array( '@id' => home_url( '/#organization' ) ),
		);
	}
	return $graph;
}

/** Breadcrumbs for the designed pages: [ name, url ] pairs after Home. */
function tristate_schema_breadcrumbs() {
	$slug    = tristate_current_slug();
	$service = $slug ? tristate_service( $slug ) : null;
	$crumbs  = array( array( 'Home', home_url( '/' ) ) );

	if ( $service ) {
		list( $group_key, $group ) = tristate_service_group( $slug );
		$crumbs[] = array( 'Our Services', tristate_page_url( 'our-services' ) );
		$crumbs[] = array( $group['label'], tristate_page_url( 'our-services', $group_key ) );
		if ( $group['label'] !== $service['title'] ) {
			$crumbs[] = array( $service['title'], tristate_service_url( $slug ) );
		}
	} elseif ( in_array( $slug, tristate_nav_items(), true ) ) {
		$crumbs[] = array( array_search( $slug, tristate_nav_items(), true ), tristate_page_url( $slug ) );
	} elseif ( is_singular( 'post' ) ) {
		$crumbs[] = array( 'News', get_option( 'page_for_posts' ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/our-blog/' ) );
		$crumbs[] = array( single_post_title( '', false ), get_permalink() );
	} else {
		return array();
	}

	return array(
		'@type'           => 'BreadcrumbList',
		'itemListElement' => array_map( static function ( $crumb, $i ) {
			return array( '@type' => 'ListItem', 'position' => $i + 1, 'name' => $crumb[0], 'item' => $crumb[1] );
		}, $crumbs, array_keys( $crumbs ) ),
	);
}

add_action( 'wp_head', function () {
	$slug  = tristate_current_slug();
	$graph = array();

	if ( is_front_page() || in_array( $slug, array( 'our-hospitals', 'about-us' ), true ) ) {
		$graph = tristate_schema_organisation();
	}

	if ( 'our-team' === $slug ) {
		foreach ( tristate_leaders() as $person ) {
			$graph[] = array_filter( array(
				'@type'    => 'Person',
				'name'     => $person['name'],
				'jobTitle' => $person['title'],
				'image'    => empty( $person['photo'] ) ? '' : TRISTATE_URI . '/assets/images/team/' . $person['photo'],
				'worksFor' => array( '@id' => home_url( '/#organization' ) ),
			) );
		}
	}

	if ( ! tristate_seo_plugin_active() ) {
		$crumbs = tristate_schema_breadcrumbs();
		if ( $crumbs ) {
			$graph[] = $crumbs;
		}
	}

	if ( $graph ) {
		printf(
			'<script type="application/ld+json">%s</script>' . "\n",
			wp_json_encode( array( '@context' => 'https://schema.org', '@graph' => $graph ), JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE )
		);
	}
}, 3 );

/* Sitemap and robots --------------------------------------------------------------- */

/** Retired pages redirect elsewhere, so they stay out of the sitemap. */
add_filter( 'wp_sitemaps_posts_query_args', function ( $args, $post_type ) {
	if ( 'page' !== $post_type ) {
		return $args;
	}
	$exclude = array();
	foreach ( array_keys( tristate_retired_pages() ) as $slug ) {
		$page = get_page_by_path( $slug, OBJECT, 'page' );
		if ( $page ) {
			$exclude[] = $page->ID;
		}
	}
	$args['post__not_in'] = array_merge( isset( $args['post__not_in'] ) ? (array) $args['post__not_in'] : array(), $exclude );
	return $args;
}, 10, 2 );

/** No author sitemap: it would list WordPress usernames. */
add_filter( 'wp_sitemaps_add_provider', function ( $provider, $name ) {
	return 'users' === $name ? false : $provider;
}, 10, 2 );

/** Search results and missing pages aren't worth indexing. */
add_filter( 'wp_robots', function ( $robots ) {
	if ( is_search() || is_404() ) {
		$robots['noindex'] = true;
		$robots['follow']  = true;
	}
	return $robots;
} );
