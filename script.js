document.addEventListener("DOMContentLoaded", function () {
    // Target the input field with ID "email"
    const emailInput = document.getElementById('email');

    // Only run if the email field exists on the current page
    if (emailInput) {
        
        // Dynamically create an error message span if it doesn't exist
        let errorSpan = document.getElementById('emailError');
        if (!errorSpan) {
            errorSpan = document.createElement('span');
            errorSpan.id = 'emailError';
            errorSpan.className = 'text-danger small mt-1 d-block'; // Bootstrap styling
            emailInput.parentNode.appendChild(errorSpan); // Add it right after the input
        }

        emailInput.addEventListener('blur', function() {
            const emailValue = this.value.trim();

            // 1. Normalize to Lowercase
            this.value = emailValue.toLowerCase();

            // 2. Check for common typos
            // Checks for @gamil, @gnail, @hotmali
            if (emailValue.includes('@gamil.') || emailValue.includes('@gnail.') || emailValue.includes('@hotmali.')) {
                errorSpan.textContent = '⚠️ Did you mean @gmail.com? Please check your email.';
                return false;
            }
            
            // 3. Basic format check (Must have @ and .)
            if (emailValue.length > 0 && (!emailValue.includes('@') || !emailValue.includes('.'))) {
                errorSpan.textContent = '❌ Please enter a valid email address.';
                return false;
            }

            // Clear error if valid
            errorSpan.textContent = '';
            return true;
        });
    }
});