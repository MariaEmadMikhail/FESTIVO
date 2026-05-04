document.addEventListener('DOMContentLoaded', () => {
    // Check if there is an error in URL
const params = new URLSearchParams(window.location.search);
if (params.get("error") === "1") {
    const loginError = document.getElementById("loginError");
    loginError.classList.add("show");
    }
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
        
        
    }

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        if (!username || !password) {
            if (!username) usernameInput.classList.add('invalid');
            if (!password) passwordInput.classList.add('invalid');
            return;
        }

        if (password === 'wrong' || password.length < 4 || username === 'wrong') {
            loginError.classList.add('show');
            usernameInput.classList.add('invalid');
            passwordInput.classList.add('invalid');
            form.classList.add('shake');
            setTimeout(() => form.classList.remove('shake'), 400);
            return;
        }

        startVerificationStep(false);
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

    const otpCodeInput = document.getElementById('otpCode');
    if(otpCodeInput) {
        otpCodeInput.addEventListener('input', () => {
            otpCodeInput.classList.remove('invalid');
            loginError.classList.remove('show');
        });
    }

    // 2FA VERIFICATION LOGIC
    const socialBox = document.getElementById('socialBox');
    const otpVerificationStep = document.getElementById('otpVerificationStep');
    const otpSelectionGroup = document.getElementById('otpSelectionGroup');
    const otpInputGroup = document.getElementById('otpInputGroup');
    const requestOtpBtn = document.getElementById('requestOtpBtn');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendCodeLink = document.getElementById('resendCodeLink');
    const otpMethodRadios = document.querySelectorAll('input[name="otpMethod"]');
    const verificationCodeInput = document.getElementById('verificationCode');
    const verificationError = document.getElementById('verificationError');

    let isAdminLogin = false;

    function startVerificationStep(admin) {
        isAdminLogin = admin;
        form.style.display = 'none';
        if(socialBox) socialBox.style.display = 'none';
        if(otpVerificationStep) otpVerificationStep.style.display = 'block';
    }

    const googleLoginBtn = document.getElementById('googleLoginBtn');
    if (googleLoginBtn) {
        googleLoginBtn.addEventListener('click', () => {
            const btnOriginal = googleLoginBtn.innerHTML;
            googleLoginBtn.innerHTML = 'Authenticating...';
            setTimeout(() => {
                googleLoginBtn.innerHTML = btnOriginal;
                startVerificationStep(false);
            }, 800);
        });
    }

    if (otpMethodRadios.length > 0) {
        otpMethodRadios.forEach(radio => {
            radio.addEventListener('change', (e) => {
                otpMethodRadios.forEach(r => {
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

    if (requestOtpBtn) {
        requestOtpBtn.addEventListener('click', () => {
            const methodEl = document.querySelector('input[name="otpMethod"]:checked');
            const method = methodEl ? methodEl.value : 'email';
            requestOtpBtn.innerHTML = 'Sending...';
            
            setTimeout(() => {
                otpSelectionGroup.style.display = 'none';
                otpInputGroup.style.display = 'block';
                alert(`Verification code sent via ${method.toUpperCase()}! (Use 123456)`);
            }, 600);
        });
    }

    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener('click', () => {
            const code = verificationCodeInput.value.trim();
            if (code !== '123456') {
                verificationError.style.display = 'block';
                verificationCodeInput.classList.add('invalid');
                return;
            }
            
            verificationError.style.display = 'none';
            verifyOtpBtn.innerHTML = 'Verifying... ✓';
            verifyOtpBtn.style.background = 'var(--success)';
            
            setTimeout(() => {
                if (isAdminLogin) {
                    alert('Verified! Logged in successfully as Admin.');
                    window.location.href = '../admin_home/index.html';
                } else {
                    alert('Verified! Logged in successfully.');
                    window.location.href = '../customer_home/index.html';
                }
            }, 1000);
        });
    }

    if (resendCodeLink) {
        resendCodeLink.addEventListener('click', (e) => {
            e.preventDefault();
            alert('A new verification code has been sent!');
        });
    }
    
    if (verificationCodeInput) {
        verificationCodeInput.addEventListener('input', () => {
            verificationCodeInput.classList.remove('invalid');
            verificationError.style.display = 'none';
        });
    }
});
