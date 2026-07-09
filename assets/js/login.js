document.addEventListener("DOMContentLoaded", () => {
  const password = document.getElementById("password");
  const toggle = document.getElementById("togglePassword");

  if (password) {
    document.querySelector('[name="username"]')?.focus();
  }

  if (!toggle) return;

  toggle.addEventListener("click", () => {
    const hidden = password.type === "password";

    password.type = hidden ? "text" : "password";

    toggle.innerHTML = hidden
      ? '<i class="fas fa-eye-slash"></i>'
      : '<i class="fas fa-eye"></i>';
  });
});
