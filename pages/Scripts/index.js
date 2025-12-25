const authModal = document.getElementById('auth-modal');
const btnLogin = document.getElementById('btn-login');
const btnRegister = document.getElementById('btn-register');
const modalTitle = document.getElementById('modal-title');
const toggleAuth = document.getElementsByClassName('toggle-auth');
const registerForm = document.getElementById('register-form');
const loginForm = document.getElementById('login-form');
const notification = document.getElementById('notification');
var isLoginMode;


setTimeout(() => {
    try {
        notification.style.display = 'none';
    } catch { }
}, 2000);


function openModal(mode) {
    isLoginMode = (mode === 'login');
    modalTitle.textContent = isLoginMode ? 'Connexion' : 'Inscription';
    for(let t of toggleAuth) t.textContent = isLoginMode ? "Pas encore de compte ? S'inscrire" : "Déjà un compte ? Se connecter";
    authModal.classList.remove('hidden');
    if (isLoginMode) { loginForm.classList.remove('hidden'); } else { registerForm.classList.remove('hidden'); }
    authModal.classList.add('flex');
}

function closeModal() {
    authModal.classList.add('hidden');
    loginForm.classList.add('hidden');
    registerForm.classList.add('hidden');
    authModal.classList.remove('flex');
}

btnLogin.addEventListener('click', () => openModal('login'));
btnRegister.addEventListener('click', () => openModal('register'));

for (let btn of toggleAuth) {
    btn.addEventListener('click', () => {
        loginForm.classList.add('hidden');
        registerForm.classList.add('hidden');
        authModal.classList.remove('flex');
        openModal(isLoginMode ? 'register' : 'login');
    });
}

function openAsaadModal() {
    const modal = document.getElementById('modal-asaad');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeAsaadModal() {
    const modal = document.getElementById('modal-asaad');
    modal.classList.add('hidden');
    document.body.style.overflow = 'auto';
}