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
        $custom_content = '
        <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); padding: 40px 20px; border-radius: 15px; margin: 20px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.2);">
            <div style="text-align: center; color: white;">
                <h1 style="font-size: 2.5em; margin-bottom: 20px; text-shadow: 2px 2px 4px rgba(0,0,0,0.3);">🚀 Welcome to Our Platform</h1>
                
                <div style="background: rgba(255,255,255,0.1); padding: 30px; border-radius: 10px; margin: 20px 0; backdrop-filter: blur(10px);">
                    <h2 style="font-size: 1.8em; margin-bottom: 15px; color: #fff;">✨ Exclusive Mobile Experience</h2>
                    <p style="font-size: 1.1em; line-height: 1.6; margin-bottom: 15px;">Discover amazing content tailored specifically for mobile users like you!</p>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 15px; margin: 25px 0;">
                        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px;">
                            <h3 style="margin: 0 0 10px 0; color: #fff;">🎯 Premium Features</h3>
                            <p style="margin: 0; font-size: 0.9em;">Access exclusive mobile-only content</p>
                        </div>
                        <div style="background: rgba(255,255,255,0.2); padding: 15px; border-radius: 8px;">
                            <h3 style="margin: 0 0 10px 0; color: #fff;">📱 Mobile Optimized</h3>
                            <p style="margin: 0; font-size: 0.9em;">Perfect experience on your device</p>
                        </div>
                    </div>
                    
                    <div style="background: rgba(255,255,255,0.15); padding: 20px; border-radius: 8px; margin: 20px 0;">
                        <h3 style="margin: 0 0 15px 0; color: #fff;">🔥 What You Get:</h3>
                        <ul style="text-align: left; margin: 0; padding-left: 20px; color: #fff;">
                            <li style="margin-bottom: 8px;">🎨 Beautiful mobile interface</li>
                            <li style="margin-bottom: 8px;">⚡ Fast loading experience</li>
                            <li style="margin-bottom: 8px;">🔒 Secure browsing</li>
                            <li style="margin-bottom: 8px;">💎 Premium content access</li>
                        </ul>
                    </div>
                </div>
                
                <div style="margin-top: 30px;">
                    <button style="background: linear-gradient(45deg, #ff6b6b, #ee5a24); color: white; border: none; padding: 15px 30px; border-radius: 25px; font-size: 1.1em; font-weight: bold; cursor: pointer; box-shadow: 0 5px 15px rgba(0,0,0,0.2); transition: all 0.3s ease;">
                        🚀 Get Started Now
                    </button>
                </div>
                
                <p style="margin-top: 25px; font-size: 0.9em; opacity: 0.8;">Thank you for choosing our mobile-optimized experience!</p>
            </div>
        </div>';
        
        return $custom_content;
    }
    
    return $content;
}
add_filter('the_content', 'modify_post_content_for_mobile_users');