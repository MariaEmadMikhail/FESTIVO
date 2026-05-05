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

        // Simplified validation for testing purposes
        // Only reject if it explicitly says 'wrong'
        if (username.toLowerCase() === 'wrong' || password === 'wrong') {
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

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const username = usernameInput.value.trim();
        const password = passwordInput.value;

        if (!username || !password) {
            if (!username) usernameInput.classList.add('invalid');
            if (!password) passwordInput.classList.add('invalid');
            return;
        }

        const submitBtn = form.querySelector('.user-btn');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = 'Checking...';
        submitBtn.disabled = true;

        try {
            const response = await fetch('../backend/login_init.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ username, password })
            });
            const result = await response.json();

            if (result.status === 'otp_sent') {
                // Show email in UI for better UX
                const otpDesc = otpVerificationStep.querySelector('p');
                if (otpDesc) otpDesc.innerText = `We sent a code to ${result.email}`;
                
                // For testing purposes, alert the code (since mail() might not be configured)
                if (result.debug_otp) {
                   console.log("Debug OTP:", result.debug_otp);
                   alert(`Verification code sent! (Debug: ${result.debug_otp})`);
                }

                startVerificationStep(false);
            } else {
                loginError.innerText = result.message || 'Invalid username or password.';
                loginError.classList.add('show');
                form.classList.add('shake');
                setTimeout(() => form.classList.remove('shake'), 400);
            }
        } catch (error) {
            console.error("Login Init Error:", error);
            alert("Connection error. Please try again.");
        } finally {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }
    });

    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener('click', async () => {
            const otp = verificationCodeInput.value.trim();
            if (!otp) {
                verificationCodeInput.classList.add('invalid');
                return;
            }
            
            verifyOtpBtn.innerHTML = 'Verifying...';
            verifyOtpBtn.disabled = true;

            try {
                const response = await fetch('../backend/login_verify.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ otp })
                });
                const result = await response.json();

                if (result.status === 'success') {
                    verifyOtpBtn.innerHTML = 'Success! ✓';
                    verifyOtpBtn.style.background = 'var(--success)';
                    setTimeout(() => {
                        window.location.href = '../customer_home/index.php';
                    }, 1000);
                } else {
                    verificationError.innerText = result.message || 'Invalid code.';
                    verificationError.style.display = 'block';
                    verificationCodeInput.classList.add('invalid');
                    verifyOtpBtn.innerHTML = 'Verify & Proceed';
                    verifyOtpBtn.disabled = false;
                }
            } catch (error) {
                console.error("OTP Verify Error:", error);
                alert("Connection error. Please try again.");
                verifyOtpBtn.disabled = false;
            }
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
