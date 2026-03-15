document.addEventListener('DOMContentLoaded', () => {
    const form = document.getElementById('login-form');
    const usernameInput = document.getElementById('username');
    const passwordInput = document.getElementById('password');
    const loginError = document.getElementById('loginError');
    const adminLoginBtn = document.getElementById('adminLoginBtn');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    function handleLogin(isAdmin) {
        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        // Reset error states
        loginError.classList.remove('show');
        usernameInput.classList.remove('invalid');
        passwordInput.classList.remove('invalid');

        // Check if fields are empty
        if (!username || !password) {
            if (!username) usernameInput.classList.add('invalid');
            if (!password) passwordInput.classList.add('invalid');
            return;
        }

        // Simulate authentication error for demonstration
        // e.g., if you type "wrong" or a very short password, it shows the error
        if (password === 'wrong' || password.length < 4 || username === 'wrong') {
            loginError.classList.add('show');
            usernameInput.classList.add('invalid');
            passwordInput.classList.add('invalid');
            
            // Add shake animation to the form
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 400);
            return;
        }

        // Success Path
        const btn = isAdmin ? adminLoginBtn : form.querySelector('.user-btn');
        const originalText = btn.innerHTML;
        btn.innerHTML = 'Connecting... ✓';
        
        if (isAdmin) {
            btn.style.background = 'var(--admin-gradient)';
            btn.style.borderColor = 'transparent';
            btn.style.color = 'white';
        } else {
            btn.style.background = 'var(--success)';
        }
        
        setTimeout(() => {
            // Route to appropriate dashboard
            if (isAdmin) {
                alert('Logged in successfully as Admin! Welcome back to Festivo.');
                window.location.href = '../admin_home/index.html';
            } else {
                const storedRole = localStorage.getItem('userRole') || 'customer';
                alert(`Logged in successfully as ${storedRole === 'provider' ? 'Provider' : 'Customer'}! Welcome back to Festivo.`);
                if (storedRole === 'provider') {
                    window.location.href = '../provider_home/index.html';
                } else {
                    window.location.href = '../customer_home/index.html';
                }
            }
        }, 1000);
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        handleLogin(false); // User login
    });

    adminLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        // HTML5 Validation trigger before Admin flow
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        handleLogin(true); // Admin login
    });

    forgotPasswordLink.addEventListener('click', (e) => {
        e.preventDefault();
        const username = usernameInput.value.trim();
        
        if (username) {
            alert(`Password reset instructions have been sent to the email associated with '${username}'.`);
        } else {
            alert('Please enter your username first to retrieve your password.');
            usernameInput.focus();
            usernameInput.classList.add('invalid');
            setTimeout(() => usernameInput.classList.remove('invalid'), 2000);
        }
    });

    // Clear invalid styling when user types
    usernameInput.addEventListener('input', () => {
        usernameInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });

    passwordInput.addEventListener('input', () => {
        passwordInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });
});
