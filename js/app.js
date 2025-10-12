// Main application initialization
document.addEventListener("DOMContentLoaded", () => {
  initTabs()
  initPasswordToggles()
  initPasswordValidation()
  initForms()
  initSuggestPassword()
})

// Tab switching functionality
function initTabs() {
  const tabButtons = document.querySelectorAll(".tab-button")
  const formContainers = document.querySelectorAll(".form-container")

  tabButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const targetTab = button.dataset.tab

      // Update active tab button
      tabButtons.forEach((btn) => btn.classList.remove("active"))
      button.classList.add("active")

      // Switch form containers with animation
      formContainers.forEach((container) => {
        container.classList.remove("active")
      })

      // Small delay for smooth transition
      setTimeout(() => {
        const targetContainer = document.querySelector(`[data-form="${targetTab}"]`)
        targetContainer.classList.add("active")

        // Focus first input in the new form
        const firstInput = targetContainer.querySelector("input")
        if (firstInput) {
          firstInput.focus()
        }
      }, 100)
    })
  })
}

// Password visibility toggle
function initPasswordToggles() {
  const toggleButtons = document.querySelectorAll(".toggle-password")

  toggleButtons.forEach((button) => {
    button.addEventListener("click", () => {
      const targetId = button.dataset.target
      const passwordInput = document.getElementById(targetId)
      const toggleText = button.querySelector(".toggle-text")

      if (passwordInput.type === "password") {
        passwordInput.type = "text"
        toggleText.textContent = "Ocultar"
      } else {
        passwordInput.type = "password"
        toggleText.textContent = "Mostrar"
      }
    })
  })
}

// Real-time password validation
function initPasswordValidation() {
  const passwordInput = document.getElementById("registro-password")
  const rules = document.querySelectorAll(".rule")

  if (!passwordInput) return

  passwordInput.addEventListener("input", () => {
    validatePassword(passwordInput.value)
  })

  function validatePassword(password) {
    const validations = {
      length: password.length >= 8,
      number: /\d/.test(password),
      special: (password.match(/[^a-zA-Z0-9]/g) || []).length >= 2,
      uppercase: (password.match(/[A-Z]/g) || []).length >= 3,
    }

    rules.forEach((rule) => {
      const ruleType = rule.dataset.rule
      if (validations[ruleType]) {
        rule.classList.add("valid")
      } else {
        rule.classList.remove("valid")
      }
    })

    return Object.values(validations).every(Boolean)
  }
}

// Password suggestion generator
function initSuggestPassword() {
  const suggestButton = document.querySelector(".suggest-password")

  if (!suggestButton) return

  suggestButton.addEventListener("click", () => {
    const targetId = suggestButton.dataset.target
    const passwordInput = document.getElementById(targetId)

    const suggestedPassword = generateSecurePassword()
    passwordInput.value = suggestedPassword

    // Trigger validation
    passwordInput.dispatchEvent(new Event("input"))

    // Show success feedback
    suggestButton.style.transform = "scale(0.95)"
    setTimeout(() => {
      suggestButton.style.transform = ""
    }, 150)
  })
}

// Generate password that meets all requirements
function generateSecurePassword() {
  const lowercase = "abcdefghijklmnopqrstuvwxyz"
  const uppercase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
  const numbers = "0123456789"
  const specials = "!@#$%^&*()_+-=[]{}|;:,.<>?"

  let password = ""

  // Ensure minimum requirements
  // Add 3 uppercase letters
  for (let i = 0; i < 3; i++) {
    password += uppercase[Math.floor(Math.random() * uppercase.length)]
  }

  // Add 2 special characters
  for (let i = 0; i < 2; i++) {
    password += specials[Math.floor(Math.random() * specials.length)]
  }

  // Add 1 number
  password += numbers[Math.floor(Math.random() * numbers.length)]

  // Fill remaining characters to reach 12 total
  const allChars = lowercase + uppercase + numbers + specials
  while (password.length < 12) {
    password += allChars[Math.floor(Math.random() * allChars.length)]
  }

  // Shuffle the password
  return password
    .split("")
    .sort(() => Math.random() - 0.5)
    .join("")
}

// Form submission handling
function initForms() {
  const loginForm = document.getElementById("login-form")
  const registroForm = document.getElementById("registro-form")

  if (loginForm) {
    loginForm.addEventListener("submit", handleLoginSubmit)
  }

  if (registroForm) {
    registroForm.addEventListener("submit", handleRegistroSubmit)
  }
}

// Login form submission
// Función para manejar el envío del formulario de login
function handleLoginSubmit(event) {
    const form = event.target;
    const formData = new FormData(form);
    const phone = formData.get("phone");
    const password = formData.get("password");

    // Limpiar errores previos
    clearFormErrors(form);

    // Validar los campos
    let hasErrors = false;

    // Validación del teléfono
    if (!validatePhone(phone)) {
        showFieldError("login-phone-error", "Ingresa un número de teléfono válido");
        hasErrors = true;
    }

    // Validación de la contraseña
    if (!password) {
        showFieldError("login-password-error", "La contraseña es requerida");
        hasErrors = true;
    }

    // --- Decisión Clave ---
    // Si se encontraron errores durante la validación...
    if (hasErrors) {
        // 1. Prevenimos que el formulario se envíe al servidor
        event.preventDefault(); 
        
        // 2. (Opcional) Mostramos una animación para notificar al usuario
        shakeForm(form);
    }
    
}

// Asegúrate de vincular esta función al evento 'submit' de tu formulario
const loginForm = document.getElementById('loginForm'); // Asegúrate de que tu form tenga id="loginForm"
if (loginForm) {
    loginForm.addEventListener('submit', handleLoginSubmit);
}

// Registration form submission
async function handleRegistroSubmit(event) {
  event.preventDefault();

  const form = event.target;
  const formData = new FormData(form);
  const name = formData.get("name");
  const phone = formData.get("phone");
  const password = formData.get("password");

  // Clear previous errors
  clearFormErrors(form);

  // Validate fields (Client-side validation)
  let hasErrors = false;

  if (!name || name.trim().length < 2) {
    showFieldError("registro-name-error", "Ingresa tu nombre completo");
    hasErrors = true;
  }

  if (!validatePhone(phone)) {
    showFieldError("registro-phone-error", "Ingresa un número de teléfono válido");
    hasErrors = true;
  }

  if (!validatePasswordComplete(password)) {
    showFieldError("registro-password-error", "La contraseña debe cumplir todos los requisitos");
    hasErrors = true;
  }

  if (hasErrors) {
    shakeForm(form);
    return;
  }

  // Show loading state
  setFormLoading(form, true);

  // --- INICIO DEL CAMBIO: LLAMADA REAL AL BACKEND ---
  try {
    // Hacemos la llamada real a nuestro script PHP usando fetch
    const response = await fetch('../php/login_register/register.php', {
      method: 'POST',
      body: formData
    });

    // Convertimos la respuesta del servidor de JSON a un objeto de JavaScript
    const result = await response.json();

    if (result.success) {
      // Si el backend confirma que el registro fue exitoso...
      showToast(result.message, "success");
      form.reset();

      // Reseteamos las reglas de validación visual de la contraseña
      document.querySelectorAll(".rule").forEach((rule) => rule.classList.remove("valid"));

      // Opcional: Redirigimos al usuario a la página del curso después de 2 segundos
      setTimeout(() => {
        window.location.href = 'curso/';
      }, 2000);

    } else {
      // Si el backend reporta un error (ej: teléfono ya existe), lo mostramos
      showToast(result.message, "error");
    }

  } catch (error) {
    // Este bloque se ejecuta si hay un error de red (ej: el servidor no responde)
    console.error('Error en la petición de registro:', error);
    showToast("Error de conexión. No se pudo contactar al servidor.", "error");
  } finally {
    // Esto se ejecuta siempre, haya éxito o error, para ocultar el spinner de carga
    setFormLoading(form, false);
  }
  // --- FIN DEL CAMBIO ---
}
// Phone validation
function validatePhone(phone) {
  if (!phone) return false
  // Allow optional + at start, then only digits
  const phoneRegex = /^\+?\d{8,15}$/
  return phoneRegex.test(phone)
}

// Complete password validation
function validatePasswordComplete(password) {
  if (!password) return false

  const hasLength = password.length >= 8
  const hasNumber = /\d/.test(password)
  const hasSpecial = (password.match(/[^a-zA-Z0-9]/g) || []).length >= 2
  const hasUppercase = (password.match(/[A-Z]/g) || []).length >= 3

  return hasLength && hasNumber && hasSpecial && hasUppercase
}

// Form error handling
function showFieldError(errorId, message) {
  const errorElement = document.getElementById(errorId)
  if (errorElement) {
    errorElement.textContent = message
    errorElement.classList.add("show")
  }
}

function clearFormErrors(form) {
  const errorElements = form.querySelectorAll(".error-message")
  errorElements.forEach((element) => {
    element.textContent = ""
    element.classList.remove("show")
  })
}

function shakeForm(form) {
  form.style.animation = "shake 0.5s ease-in-out"
  setTimeout(() => {
    form.style.animation = ""
  }, 500)
}

// Loading state management
function setFormLoading(form, isLoading) {
  const submitButton = form.querySelector(".submit-button")
  const buttonText = submitButton.querySelector(".button-text")
  const buttonSpinner = submitButton.querySelector(".button-spinner")

  if (isLoading) {
    submitButton.disabled = true
    submitButton.setAttribute("aria-busy", "true")
    buttonText.style.display = "none"
    buttonSpinner.style.display = "block"
  } else {
    submitButton.disabled = false
    submitButton.removeAttribute("aria-busy")
    buttonText.style.display = "block"
    buttonSpinner.style.display = "none"
  }
}

// Toast notifications
function showToast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container")

  const toast = document.createElement("div")
  toast.className = `toast ${type}`

  toast.innerHTML = `
        <span>${message}</span>
        <button class="toast-close" aria-label="Cerrar notificación">&times;</button>
    `

  // Add close functionality
  const closeButton = toast.querySelector(".toast-close")
  closeButton.addEventListener("click", () => {
    removeToast(toast)
  })

  toastContainer.appendChild(toast)

  // Auto-remove after 4 seconds
  setTimeout(() => {
    removeToast(toast)
  }, 4000)
}

// function removeToast(toast) {
//   if (toast && toast.parentNode) {
//     toast.style.animation = "toastEnter 0.3s ease-out reverse"
//     setTimeout(() => {
//       toast.remove()
//     }, 300)
//   }
// }
// ... (código JavaScript existente) ...

// Toast notifications
function showToast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");

  // Añadimos la clase 'active' para mostrar el fondo y la alerta
  toastContainer.classList.add("active");

  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${message}</span>`;

  // Limpiamos el contenedor antes de añadir una nueva alerta
  toastContainer.innerHTML = ''; 
  toastContainer.appendChild(toast);

  // Auto-remove after 2 seconds
  setTimeout(() => {
    // Eliminamos la clase 'active' para ocultar con una animación
    toastContainer.classList.remove("active");
  }, 2000); // 2000 milisegundos = 2 segundos
}

// La función removeToast ya no es necesaria con este nuevo enfoque
/* function removeToast(toast) {
  if (toast && toast.parentNode) {
    toast.style.animation = "toastEnter 0.3s ease-out reverse";
    setTimeout(() => {
      toast.remove();
    }, 300);
  }
}
*/