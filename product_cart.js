function addToCart(pid) {
    fetch("add_to_cart.php?pid=" + pid)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // 1. Update the cart count badge immediately
                let cartCountElement = document.getElementById("cart-count");
                if(cartCountElement) {
                    cartCountElement.innerText = data.cart_count;
                }

                // 2. Show Success Toast Notification
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: data.message, // "Product added to cart"
                    showConfirmButton: false,
                    timer: 2000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

            } else {
                // 3. Show Error Alert (Center Modal)
                Swal.fire({
                    icon: 'error',
                    title: 'Oops...',
                    text: data.message,
                });
            }
        })
        .catch(error => {
            console.error("Error:", error);
            Swal.fire({
                icon: 'error',
                title: 'Network Error',
                text: 'Something went wrong. Please try again.',
            });
        });
}