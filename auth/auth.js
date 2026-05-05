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
    startSignupVerificationStep();
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
            loginError.innerText = "Invalid credentials. Please try again.";
            loginError.classList.add('show');
            loginUsernameInput.classList.add('invalid');
            loginPasswordInput.classList.add('invalid');
            
            loginForm.classList.add('shake');
            setTimeout(() => loginForm.classList.remove('shake'), 400);
            return;
        }

        if (isAdmin) {
            if (username !== 'festivo26' && username !== 'nourmmmr') {
                loginError.innerText = "Access denied. Only authorized administrators can login.";
                loginError.classList.add('show');
                loginUsernameInput.classList.add('invalid');
                loginPasswordInput.classList.add('invalid');
                loginForm.classList.add('shake');
                setTimeout(() => loginForm.classList.remove('shake'), 400);
                return;
            }
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
        
        const username = loginUsernameInput.value.trim();
        const password = loginPasswordInput.value;

        loginError.classList.remove('show');
        loginUsernameInput.classList.remove('invalid');
        loginPasswordInput.classList.remove('invalid');

        if (!username || !password) {
            if (!username) loginUsernameInput.classList.add('invalid');
            if (!password) loginPasswordInput.classList.add('invalid');
            return;
        }

        if (password === 'wrong' || password.length < 4 || username === 'wrong') {
            loginError.innerText = "Invalid credentials. Please try again.";
            loginError.classList.add('show');
            loginUsernameInput.classList.add('invalid');
            loginPasswordInput.classList.add('invalid');
            loginForm.classList.add('shake');
            setTimeout(() => loginForm.classList.remove('shake'), 400);
            return;
        }

        startLoginVerificationStep(false);
    });

    adminLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!loginForm.checkValidity()) {
            loginForm.reportValidity();
            return;
        }
        startLoginVerificationStep(true);
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

    // ==========================================
    // 2FA VERIFICATION LOGIC FOR SIGNUP AND LOGIN
    // ==========================================

    // SIGNUP VERIFICATION ELEMENTS
    const signupSocialBox = document.getElementById('signupSocialBox');
    const signupOtpVerifStep = document.getElementById('signupOtpVerifStep');
    const signupOtpSelGrp = document.getElementById('signupOtpSelGrp');
    const signupOtpInpGrp = document.getElementById('signupOtpInpGrp');
    const reqSignupOtpBtn = document.getElementById('reqSignupOtpBtn');
    const verifySignupOtpBtn = document.getElementById('verifySignupOtpBtn');
    const signupVerifyCode = document.getElementById('signupVerifyCode');
    const signupVerifyError = document.getElementById('signupVerifyError');
    const googleSignupBtn = document.getElementById('googleSignupBtn');
    const resendSignupCodeLink = document.getElementById('resendSignupCodeLink');
    const signupOtpMethodRadios = document.querySelectorAll('input[name="signupOtpMethod"]');

    if (reqSignupOtpBtn) {
    reqSignupOtpBtn.addEventListener('click', async () => {

        const emailValue = email.value;

        if (!emailValue) {
            alert("Enter your email first");
            return;
        }

        reqSignupOtpBtn.innerHTML = 'Sending...';

        try {
            const response = await fetch('/FESTIVO/backend/send_otp.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({
                    email: emailValue
                })
            });

            const data = await response.json();

            if (data.status === 'otp_sent') {
                signupOtpSelGrp.style.display = 'none';
                signupOtpInpGrp.style.display = 'block';
            } else {
                alert("Failed to send OTP");
            }

        } catch (error) {
            console.error(error);
            alert("Server error");
        }
    });
}

    function startSignupVerificationStep() {
        signupForm.style.display = 'none';
        if(signupSocialBox) signupSocialBox.style.display = 'none';
        if(signupOtpVerifStep) signupOtpVerifStep.style.display = 'block';
    }

    if (googleSignupBtn) {
        googleSignupBtn.addEventListener('click', () => {
            const temp = googleSignupBtn.innerHTML;
            googleSignupBtn.innerHTML = 'Authenticating...';
            setTimeout(() => {
                googleSignupBtn.innerHTML = temp;
                startSignupVerificationStep();
            }, 800);
        });
    }

    if (signupOtpMethodRadios.length > 0) {
        signupOtpMethodRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                signupOtpMethodRadios.forEach(r => {
                    r.parentElement.style.borderColor = 'var(--border)';
                    r.parentElement.style.background = 'transparent';
                });
                if(e.target.checked) {
                    e.target.parentElement.style.borderColor = 'var(--secondary)';
                    e.target.parentElement.style.background = 'rgba(0, 206, 201, 0.1)';
                }
            });
        });
    }

    

    if (verifySignupOtpBtn) {
    verifySignupOtpBtn.addEventListener('click', async () => {

        const otp = signupVerifyCode.value;

        if (!otp) {
            signupVerifyCode.classList.add('invalid');
            signupVerifyError.style.display = 'block';
            return;
        }

        const response = await fetch('/FESTIVO/backend/signup.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({
                action: "verify_otp",
                otp: otp
            })
        });

        const result = await response.json();

        if (result.status === "success") {
            window.location.href = "login.html";
        } else {
            signupVerifyError.style.display = 'block';
        }
    });
}   

    if (resendSignupCodeLink) resendSignupCodeLink.addEventListener('click', (e) => { e.preventDefault(); alert("New code sent!"); });
    if (signupVerifyCode) signupVerifyCode.addEventListener('input', () => { signupVerifyCode.classList.remove('invalid'); signupVerifyError.style.display = 'none'; });


    // LOGIN VERIFICATION ELEMENTS
    const loginSocialBox = document.getElementById('loginSocialBox');
    const loginOtpVerifStep = document.getElementById('loginOtpVerifStep');
    const loginOtpSelGrp = document.getElementById('loginOtpSelGrp');
    const loginOtpInpGrp = document.getElementById('loginOtpInpGrp');
    const reqLoginOtpBtn = document.getElementById('reqLoginOtpBtn');
    const verifyLoginOtpBtn = document.getElementById('verifyLoginOtpBtn');
    const loginVerifyCode = document.getElementById('loginVerifyCode');
    const loginVerifyError = document.getElementById('loginVerifyError');
    const googleLoginBtn = document.getElementById('googleLoginBtn');
    const resendLoginCodeLink = document.getElementById('resendLoginCodeLink');
    const loginOtpMethodRadios = document.querySelectorAll('input[name="loginOtpMethod"]');

    let isAdminLoginFlow = false;

    function startLoginVerificationStep(isAdmin) {
        isAdminLoginFlow = isAdmin;
        loginForm.style.display = 'none';
        if(loginSocialBox) loginSocialBox.style.display = 'none';
        if(loginOtpVerifStep) loginOtpVerifStep.style.display = 'block';
    }

    if (googleLoginBtn) {
        googleLoginBtn.addEventListener('click', () => {
            const temp = googleLoginBtn.innerHTML;
            googleLoginBtn.innerHTML = 'Authenticating...';
            setTimeout(() => {
                googleLoginBtn.innerHTML = temp;
                startLoginVerificationStep(false);
            }, 800);
        });
    }

    if (loginOtpMethodRadios.length > 0) {
        loginOtpMethodRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                loginOtpMethodRadios.forEach(r => {
                    r.parentElement.style.borderColor = 'var(--border)';
                    r.parentElement.style.background = 'transparent';
                });
                if(e.target.checked) {
                    e.target.parentElement.style.borderColor = 'var(--secondary)';
                    e.target.parentElement.style.background = 'rgba(0, 206, 201, 0.1)';
                }
            });
        });
    }

    if (reqLoginOtpBtn) {
        reqLoginOtpBtn.addEventListener('click', () => {
            const checked = document.querySelector('input[name="loginOtpMethod"]:checked');
            const method = checked ? checked.value : 'email';
            reqLoginOtpBtn.innerHTML = 'Sending...';
            setTimeout(() => {
                loginOtpSelGrp.style.display = 'none';
                loginOtpInpGrp.style.display = 'block';
                alert(`Verification code sent via ${method.toUpperCase()}! (Use 123456)`);
            }, 600);
        });
    }

    if (verifyLoginOtpBtn) {
        verifyLoginOtpBtn.addEventListener('click', () => {
            if (loginVerifyCode.value.trim() !== '123456') {
                loginVerifyCode.classList.add('invalid');
                loginVerifyError.style.display = 'block';
                return;
            }
            loginVerifyError.style.display = 'none';
            verifyLoginOtpBtn.innerHTML = 'Verifying... ✓';
            verifyLoginOtpBtn.style.background = 'var(--success)';
            setTimeout(() => {
                if (isAdminLoginFlow) {
                    alert('Verified! Logged in successfully as Admin.');
                    window.location.href = '../admin_home/index.html';
                } else {
                    alert('Verified! Logged in successfully.');
                    window.location.href = '../customer_home/index.html';
                }
            }, 1000);
        });
    }

    if (resendLoginCodeLink) resendLoginCodeLink.addEventListener('click', (e) => { e.preventDefault(); alert("New code sent!"); });
    if (loginVerifyCode) loginVerifyCode.addEventListener('input', () => { loginVerifyCode.classList.remove('invalid'); loginVerifyError.style.display = 'none'; });
});
