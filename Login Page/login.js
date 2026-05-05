document.addEventListener('DOMContentLoaded', () => {
    // Check if there is an error in URL
const params = new URLSearchParams(window.location.search);
if (params.get("error") === "1") {
    const loginError = document.getElementById("loginError");
    loginError.classList.add("show");
    }
    const form = document.getElementById('login-form');
    const usernameInput = document.getElementById('loginUsername');
    const passwordInput = document.getElementById('loginPassword');
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
            e.preventDefault();

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
                const otpDesc = document.getElementById('otpVerificationStep').querySelector('p');
                if (otpDesc) otpDesc.innerText = `We sent a code to ${result.email}`;
                
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

    adminLoginBtn.addEventListener('click', (e) => {
        e.preventDefault();
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        handleLogin(true);
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

    usernameInput.addEventListener('input', () => {
        usernameInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });

    passwordInput.addEventListener('input', () => {
        passwordInput.classList.remove('invalid');
        loginError.classList.remove('show');
    });

    const socialBox = document.getElementById('socialBox');
    const otpVerificationStep = document.getElementById('otpVerificationStep');
    const verifyOtpBtn = document.getElementById('verifyOtpBtn');
    const resendCodeLink = document.getElementById('resendCodeLink');
    const verificationCodeInput = document.getElementById('verificationCode');
    const verificationError = document.getElementById('verificationError');

    function startVerificationStep(admin) {
        form.style.display = 'none';
        if(socialBox) socialBox.style.display = 'none';
        if(otpVerificationStep) otpVerificationStep.style.display = 'block';
    }

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
