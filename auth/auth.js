document.addEventListener('DOMContentLoaded', () => {
    
    /* =========================================
       TOGGLE BETWEEN LOGIN AND SIGNUP
       ========================================= */
    const signupSection = document.getElementById('signup-section');
    const loginSection = document.getElementById('login-section');
    const showLoginBtn = document.getElementById('showLoginBtn');
    const showSignupBtn = document.getElementById('showSignupBtn');

    // Check if user came from 'Login' button on Home Page
    if (sessionStorage.getItem('openLogin') === 'true') {
        signupSection.classList.add('hidden');
        loginSection.classList.remove('hidden');
        sessionStorage.removeItem('openLogin'); // Clear it so it doesn't stick
    }
    
    showLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        signupSection.classList.add('hidden');
        loginSection.classList.remove('hidden');
    });

    showSignupBtn.addEventListener('click', (e) => {
        e.preventDefault();
        loginSection.classList.add('hidden');
        signupSection.classList.remove('hidden');
    });

    /* =========================================
       SIGN UP LOGIC
       ========================================= */
    const signupForm = document.getElementById('signup-form');
    // Inputs
    const firstName = document.getElementById('firstName');
    const lastName = document.getElementById('lastName');
    const email = document.getElementById('email');
    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirmPassword');
    
    // Errors
    const firstNameError = document.getElementById('firstNameError');
    const lastNameError = document.getElementById('lastNameError');
    const emailError = document.getElementById('emailError');
    const passwordError = document.getElementById('passwordError');
    const confirmPasswordError = document.getElementById('confirmPasswordError');

    // Validation Regex
    const nameRegex = /^[A-Za-z]+$/;
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

    function validateField(input, errorElement, regex = null, customCheck = null) {
        let isValid = true;
        
        if (input.value.trim() === '') {
            isValid = false; 
        } else if (regex && !regex.test(input.value.trim())) {
            isValid = false;
        } else if (customCheck && !customCheck()) {
            isValid = false;
        }

        if (!isValid && input.value.trim() !== '') {
            input.classList.add('invalid');
            input.classList.remove('valid');
            errorElement.classList.add('show');
        } else if (input.value.trim() !== '') {
            input.classList.remove('invalid');
            input.classList.add('valid');
            errorElement.classList.remove('show');
        } else {
            input.classList.remove('invalid');
            input.classList.remove('valid');
            errorElement.classList.remove('show');
        }

        return isValid;
    }

    // Event Listeners for sign up validation
    firstName.addEventListener('input', () => validateField(firstName, firstNameError, nameRegex));
    lastName.addEventListener('input', () => validateField(lastName, lastNameError, nameRegex));
    email.addEventListener('input', () => validateField(email, emailError, emailRegex));

    password.addEventListener('input', () => {
        validateField(password, passwordError, null, () => password.value.length <= 12);
        if (confirmPassword.value) {
            validateField(confirmPassword, confirmPasswordError, null, () => password.value === confirmPassword.value);
        }
    });

    confirmPassword.addEventListener('input', () => {
        validateField(confirmPassword, confirmPasswordError, null, () => password.value === confirmPassword.value);
    });

    signupForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const isFirstNameValid = validateField(firstName, firstNameError, nameRegex);
        const isLastNameValid = validateField(lastName, lastNameError, nameRegex);
        const isEmailValid = validateField(email, emailError, emailRegex);
        const isPasswordValid = password.value.length > 0 && password.value.length <= 12;
        const isConfirmPasswordValid = confirmPassword.value.length > 0 && password.value === confirmPassword.value;

        if (isFirstNameValid && isLastNameValid && isEmailValid && isPasswordValid && isConfirmPasswordValid) {
            // Success animation
            const btn = document.getElementById('signupSubmitBtn');
            const originalText = btn.innerHTML;
            btn.innerHTML = 'Account Created! ✓';
            btn.style.background = 'var(--success)';
            
            setTimeout(() => {
                // Save the selected role for login redirect
                const selectedRole = document.querySelector('input[name="role"]:checked').value;
                localStorage.setItem('userRole', selectedRole);

                alert('Sign up successful! Please log in with your new credentials.');
                // Switch to login screen!
                signupSection.classList.add('hidden');
                loginSection.classList.remove('hidden');
                
                // Reset styling
                btn.innerHTML = originalText;
                btn.style.background = '';
                signupForm.reset(); // clear form
                
                // Clear validation outlines
                const inputs = signupForm.querySelectorAll('input');
                inputs.forEach(i => { i.classList.remove('valid', 'invalid'); });
                
            }, 800);
        } else {
            // Force show errors if empty
            validateField(firstName, firstNameError, nameRegex);
            validateField(lastName, lastNameError, nameRegex);
            validateField(email, emailError, emailRegex);
            validateField(password, passwordError, null, () => password.value.length > 0 && password.value.length <= 12);
            validateField(confirmPassword, confirmPasswordError, null, () => password.value === confirmPassword.value);
            
            signupForm.classList.add('shake');
            setTimeout(() => signupForm.classList.remove('shake'), 400);
        }
    });


    /* =========================================
       LOGIN LOGIC
       ========================================= */
    const loginForm = document.getElementById('login-form');
    const loginUsernameInput = document.getElementById('loginUsername');
    const loginPasswordInput = document.getElementById('loginPassword');
    const loginError = document.getElementById('loginError');
    const adminLoginBtn = document.getElementById('adminLoginBtn');
    const forgotPasswordLink = document.getElementById('forgotPasswordLink');

    function handleLogin(isAdmin) {
        const username = loginUsernameInput.value.trim();
        const password = loginPasswordInput.value;

        // Reset error states
        loginError.classList.remove('show');
        loginUsernameInput.classList.remove('invalid');
        loginPasswordInput.classList.remove('invalid');

        // Check if fields are empty
        if (!username || !password) {
            if (!username) loginUsernameInput.classList.add('invalid');
            if (!password) loginPasswordInput.classList.add('invalid');
            return;
        }

        // Error detection
        if (password === 'wrong' || password.length < 4 || username === 'wrong') {
            loginError.classList.add('show');
            loginUsernameInput.classList.add('invalid');
            loginPasswordInput.classList.add('invalid');
            
            loginForm.classList.add('shake');
            setTimeout(() => loginForm.classList.remove('shake'), 400);
            return;
        }

        // Success Path
        const btn = isAdmin ? adminLoginBtn : loginForm.querySelector('.user-btn');
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
                alert('Logged in successfully as Admin! Welcome to Festivo.');
                window.location.href = '../admin_home/index.html';
            } else {
                const storedRole = localStorage.getItem('userRole') || 'customer';
                alert(`Logged in successfully as ${storedRole === 'provider' ? 'Provider' : 'Customer'}! Welcome to Festivo.`);
                if (storedRole === 'provider') {
                    window.location.href = '../provider_home/index.html';
                } else {
                    window.location.href = '../customer_home/index.html';
                }
            }
        }, 1000);
    }

    loginForm.addEventListener('submit', (e) => {
        e.preventDefault();
        handleLogin(false);
    });

    adminLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!loginForm.checkValidity()) {
            loginForm.reportValidity();
            return;
        }
        handleLogin(true);
    });

    forgotPasswordLink.addEventListener('click', (e) => {
        e.preventDefault();
        const username = loginUsernameInput.value.trim();
        
        if (username) {
            alert(`Password reset instructions have been sent to the email associated with '${username}'.`);
        } else {
            alert('Please enter your username first to retrieve your password.');
            loginUsernameInput.focus();
            loginUsernameInput.classList.add('invalid');
            setTimeout(() => loginUsernameInput.classList.remove('invalid'), 2000);
        }
    });

    loginUsernameInput.addEventListener('input', () => {
        loginUsernameInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });

    loginPasswordInput.addEventListener('input', () => {
        loginPasswordInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });
});
