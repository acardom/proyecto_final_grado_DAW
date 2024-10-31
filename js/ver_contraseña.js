function togglePasswordVisibility() {
    var passwordInput = document.getElementById('password');
    var showPasswordIcon = document.querySelector('.show-password');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showPasswordIcon.style.textDecoration = 'none'; 
    } else {
        passwordInput.type = 'password';
        showPasswordIcon.style.textDecoration = 'line-through'; 
    }
}

function togglePasswordVisibility2() {
    var passwordInput = document.getElementById('password2');
    var showPasswordIcon = document.querySelector('.show-password2');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        showPasswordIcon.style.textDecoration = 'none'; 
    } else {
        passwordInput.type = 'password';
        showPasswordIcon.style.textDecoration = 'line-through'; 
    }
}

