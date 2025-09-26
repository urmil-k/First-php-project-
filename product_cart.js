
function addToCart(pid) {
    fetch("add_to_cart.php?pid=" + pid)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update cart count badge (if you have one)
                document.getElementById("cart-count").innerText = data.cart_count;
                alert(data.message);
            } else {
                alert(data.message);
            }
        })
        .catch(error => console.error("Error:", error));
}

