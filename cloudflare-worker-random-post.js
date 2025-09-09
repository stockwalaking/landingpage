// Cloudflare Worker for AdSense-safe random post redirect
// Deploy this in Cloudflare Workers dashboard

addEventListener('fetch', event => {
  event.respondWith(handleRequest(event.request))
})

async function handleRequest(request) {
  const url = new URL(request.url)
  
  // Only handle requests to /random-post
  if (url.pathname !== '/random-post') {
    return fetch(request)
  }

  try {
    // Get your WordPress site's REST API endpoint
    const wpApiUrl = 'https://yoursite.com/wp-json/wp/v2/posts?per_page=100&status=publish'
    
    const response = await fetch(wpApiUrl)
    const posts = await response.json()
    
    if (posts.length === 0) {
      return new Response('No posts available', { status: 404 })
    }
    
    // Select random post
    const randomIndex = Math.floor(Math.random() * posts.length)
    const randomPost = posts[randomIndex]
    
    // Redirect to the random post
    return Response.redirect(randomPost.link, 302)
    
  } catch (error) {
    // Fallback to homepage
    return Response.redirect('https://yoursite.com/', 302)
  }
}