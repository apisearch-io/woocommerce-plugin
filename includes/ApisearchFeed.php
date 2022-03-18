<?php

class ApisearchFeed
{
    private $products;
    private $productsVariations;

    /**
     * Register the feed with the WordPress feeds.
     * Necessary to be able to access the feed URL.
     *
     * @since 1.0.0
     */
    public static function register() {
        $class = __CLASS__;
        global $wp_rewrite;
        $wp_rewrite = $wp_rewrite ?? new wp_rewrite;
        add_feed( 'apisearch', function () use ( $class ) {
            $feed = new $class();
            $feed->generate();
        } );
    }

    /**
     * Data_Feed constructor.
     *
     * @since 1.0.0
     *
     * @param string $language Language of the feed to show.
     */
    public function __construct($xml = true, $product_ids = null, $language = null) {

        $this->load_products($product_ids, $language);
        $this->load_product_variations();


    }

    /**
     * Load all the products to be included in the feed from DB.
     *
     * @since 1.0.0
     */
    private function load_products($product_ids = null, $language = null) {
        global $woocommerce;

        $args = array(
            'post_type'   => 'product',
            'post_status' => 'publish',
            'ignore_sticky_posts' => 1,
            'posts_per_page' => - 1,
            'orderby' => 'ID',
            'order'   => 'ASC',
            'cache_results'          => false,
            'update_post_meta_cache' => false,
            'update_post_term_cache' => false,
        );

        if (is_array($product_ids) && !empty($product_ids)) {
            $args['post__in'] = $product_ids;
        }

        // Version 3+
        // Since version 3.0.0 catalog visibility became a taxonomy.
        // Whenever the product is hidden from search the term "exclude-from-search"
        // is added.
        if ( version_compare( $woocommerce->version, '3.0.0', '>=' ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => array( 'exclude-from-search' ),
                    'operator' => 'NOT IN',
                ),
            );
        }

        // Version 2+
        // In older versions visibility was stored as a post meta on the product.
        else {
            $args['meta_query'] = array(
                'relation' => 'OR',
                array(
                    'key'     => '_visibility',
                    'compare' => 'NOT EXISTS',
                ),
                array(
                    'key'     => '_visibility',
                    'value'   => array( 'search', 'visible' ),
                    'compare' => 'IN',
                ),
                array(
                    'key'   => '_visibility',
                    'value' => '',
                ),
            );
        }

        // GET parameters
        // 'limit' and 'offset' parameters create pagination.
        $limit = 0;
        if ( isset( $_GET['limit'] ) && ! empty( $_GET['limit'] ) ) {
            $limit = (int) $_GET['limit'];
            $args['posts_per_page'] = $_GET['limit'];
        }

        $query = new \WP_Query( $args );
        $this->products = $query->posts;

        echo ('Load products - Count: ' . count($this->products));
        echo ( 'Current Memory Usage: ' . memory_get_usage());
    }

    /**
     * Load all variations from DB.
     *
     * @since 1.0.0
     */
    private function load_product_variations() {

        $variations = get_posts( array(
            'post_type'      => 'product_variation',
            'posts_per_page' => - 1,

            // Only load variations for the loaded products.
            // If there are no products (e.g. offset value is too high, and we are past all
            // products) passing a negative value will ensure non variations will be loaded.
            // If we passed an empty array WordPress would load *all* variations.
            'post_parent__in' => empty( $this->products ) ? [-1] : array_map( function ( \WP_Post $post ) {
                return $post->ID;
            }, $this->products )
        ) );

        // Index by ID in order to avoid iterating the entire array when we need to retrieve a variation
        $this->productsVariations = array();
        foreach ( $variations as $variation ) {
            $this->productsVariations[$variation->ID] = $variation;
        }
    }

    private function generate()
    {
        var_dump($this->products);
    }
}
