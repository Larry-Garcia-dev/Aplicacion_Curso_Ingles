// js/recovery.js

document.addEventListener("DOMContentLoaded", () => {
  const recoveryModal = document.getElementById("recoveryModal");
  const openModalBtn = document.getElementById("open-recovery-modal");
  const closeModalBtn = document.querySelector(".recovery-close-btn");

  // Formularios y pasos
  const formStep1 = document.getElementById("recovery-form-1");
  const formStep2 = document.getElementById("recovery-form-2");
  
  // Inputs
  const phoneInput = document.getElementById("recovery-phone");
  const codeInput = document.getElementById("recovery-code");
  const pass1Input = document.getElementById("recovery-password-1");
  const pass2Input = document.getElementById("recovery-password-2");

  // Función para abrir el modal
  if (openModalBtn) {
    openModalBtn.addEventListener("click", (e) => {
      e.preventDefault();
      recoveryModal.style.display = "flex";
    });
  }

  // Función para cerrar el modal
  const closeModal = () => {
    recoveryModal.style.display = "none";
    // Resetear modal al estado inicial
    showStep(1);
    formStep1.reset();
    formStep2.reset();
    clearFormErrors(formStep1);
    clearFormErrors(formStep2);
  };

  if(closeModalBtn) {
    closeModalBtn.addEventListener("click", closeModal);
  }
  
  window.addEventListener("click", (e) => {
    if (e.target == recoveryModal) {
      closeModal();
    }
  });


  // Navegación entre pasos
  const showStep = (stepNumber) => {
    document.querySelectorAll(".recovery-step").forEach(step => step.classList.remove("active"));
    
    if (stepNumber === 'loading') {
        document.getElementById("recovery-loading-step").classList.add("active");
    } else {
        document.getElementById(`recovery-step-${stepNumber}`).classList.add("active");
    }
  };


  // --- PASO 1: Enviar número de teléfono ---
  if (formStep1) {
    formStep1.addEventListener("submit", async (e) => {
      e.preventDefault();
      const phone = phoneInput.value;
      clearFormErrors(formStep1);

      if (!validatePhone(phone)) {
        showFieldError("recovery-phone-error", "Ingresa un número de teléfono válido.");
        return;
      }
      
      // --- CORRECCIÓN AQUÍ: Se pasa el formulario (formStep1) ---
      setFormLoading(formStep1, true);

      try {
        const formData = new FormData();
        formData.append('phone', phone);
        
        const response = await fetch('php/login_register/send_recovery_code.php', {
          method: 'POST',
          body: formData,
        });

        const result = await response.json();

        if (result.success) {
          showStep('loading');
          setTimeout(() => {
            showStep(2);
          }, 5000);

        } else {
          showFieldError("recovery-phone-error", result.message || "Este número no está registrado.");
        }

      } catch (error) {
        console.error("Error:", error);
        showFieldError("recovery-phone-error", "Error de conexión. Inténtalo de nuevo.");
      } finally {
        // --- CORRECCIÓN AQUÍ: Se pasa el formulario (formStep1) ---
        setFormLoading(formStep1, false);
      }
    });
  }


  // --- PASO 2: Enviar código y nueva contraseña ---
  if (formStep2) {
    formStep2.addEventListener("submit", async (e) => {
      e.preventDefault();
      clearFormErrors(formStep2);
      
      const phone = phoneInput.value;
      const code = codeInput.value;
      const password = pass1Input.value;
      const passwordConfirm = pass2Input.value;
      
      let hasErrors = false;
      if (code.trim().length === 0) {
          showFieldError("recovery-code-error", "El código es requerido.");
          hasErrors = true;
      }
      if (password.length < 8) {
          showFieldError("recovery-password-1-error", "La contraseña debe tener al menos 8 caracteres.");
          hasErrors = true;
      }
      if (password !== passwordConfirm) {
          showFieldError("recovery-password-2-error", "Las contraseñas no coinciden.");
          hasErrors = true;
      }

      if (hasErrors) return;

      // --- CORRECCIÓN AQUÍ: Se pasa el formulario (formStep2) ---
      setFormLoading(formStep2, true);
      
      try {
          const formData = new FormData();
          formData.append('phone', phone);
          formData.append('code', code);
          formData.append('password', password);

          const response = await fetch('php/login_register/reset_password.php', {
              method: 'POST',
              body: formData
          });

          const result = await response.json();

          if (result.success) {
              showToast("Contraseña actualizada con éxito.", "success");
              setTimeout(closeModal, 1500);
          } else {
              showToast(result.message || "El código es incorrecto.", "error");
          }

      } catch (error) {
          console.error("Error:", error);
          showToast("Error de conexión. Inténtalo de nuevo.", "error");
      } finally {
          // --- CORRECCIÓN AQUÍ: Se pasa el formulario (formStep2) ---
          setFormLoading(formStep2, false);
      }
    });
  }

  // Funcionalidad para ver/ocultar contraseña
  document.querySelectorAll('.toggle-password-recovery').forEach(button => {
    button.addEventListener('click', () => {
        const targetId = button.dataset.target;
        const passwordInput = document.getElementById(targetId);
        const toggleText = button.querySelector(".toggle-text");

        if (passwordInput.type === "password") {
            passwordInput.type = "text";
            toggleText.textContent = "Ocultar";
        } else {
            passwordInput.type = "password";
            toggleText.textContent = "Mostrar";
        }
    });
  });

});

// Estas funciones de ayuda son utilizadas por recovery.js pero están definidas en app.js
// Asegúrate de que app.js se carga ANTES que recovery.js en tu index.php

function validatePhone(phone) {
  if (!phone) return false;
  const phoneRegex = /^\+?\d{8,15}$/;
  return phoneRegex.test(phone);
}

function showFieldError(errorId, message) {
  const errorElement = document.getElementById(errorId);
  if (errorElement) {
    errorElement.textContent = message;
    errorElement.classList.add("show");
  }
}

function clearFormErrors(form) {
  const errorElements = form.querySelectorAll(".error-message");
  errorElements.forEach((element) => {
    element.textContent = "";
    element.classList.remove("show");
  });
}

// Esta es la función que causaba el error. Está en app.js, pero la pongo aquí para referencia.
// No necesitas agregarla a recovery.js, solo asegúrate de que app.js esté incluido.
/*
function setFormLoading(form, isLoading) {
  const submitButton = form.querySelector(".submit-button");
  const buttonText = submitButton.querySelector(".button-text");
  const buttonSpinner = submitButton.querySelector(".button-spinner");

  if (isLoading) {
    submitButton.disabled = true;
    buttonText.style.display = "none";
    buttonSpinner.style.display = "block";
  } else {
    submitButton.disabled = false;
    buttonText.style.display = "block";
    buttonSpinner.style.display = "none";
  }
}
*/

function showToast(message, type = "success") {
  const toastContainer = document.getElementById("toast-container");
  if (!toastContainer) return;
  const toast = document.createElement("div");
  toast.className = `toast ${type}`;
  toast.innerHTML = `<span>${message}</span><button class="toast-close">&times;</button>`;
  toast.querySelector(".toast-close").addEventListener("click", () => {
    toast.remove();
  });
  toastContainer.appendChild(toast);
  setTimeout(() => {
    if(toast) {
      toast.remove();
    }
  }, 4000);
}