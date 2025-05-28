document.getElementById('togglePassword').addEventListener('click', function () {
    const passwordInput = document.getElementById('myInput');
    const icon = this;
  
    const isPassword = passwordInput.type === 'password';
    passwordInput.type = isPassword ? 'text' : 'password';
    icon.src = isPassword ? 'image/eye_open.svg' : 'image/eye_closed.svg';
  });
  