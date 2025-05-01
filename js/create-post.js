document.addEventListener("DOMContentLoaded", function () {
    // Check if post_id is in the URL, if not, create a new post
    const urlParams = new URLSearchParams(window.location.search);
    let postId = urlParams.get('new_post_id');
    if (!postId) {
        // If no post_id in URL, create a new post via AJAX
        createNewClientPost();
        console.log("No post_id found in URL. Creating a new post.");
    } else {
        console.log("Post ID found in URL:", postId);
    }
});

function createNewClientPost() {
    // Make the AJAX request to create a new client post
    fetch(wp_vars.ajax_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: new URLSearchParams({
            action: 'create_new_client_post',
            nonce: wp_vars.create_post_nonce
        })
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                const postId = data.post_id;
                // Update the URL with the new post_id and stage
                const url = new URL(window.location.href);
                url.searchParams.set('new_post_id', postId);
                url.searchParams.set('stage', 'draft');  // Start at the 'draft' stage
                window.history.pushState({}, '', url); // Update the URL without reloading
                // You can now proceed with any additional logic you want after creating the post
            } else {
                console.error("Failed to create post:", data.message);
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
}
