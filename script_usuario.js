document.addEventListener("DOMContentLoaded", function () {
  const loginMessage = document.getElementById("loginMessage");

  if (loginMessage) {
    // Oculta a mensagem após 5 segundos
    setTimeout(() => {
      loginMessage.style.transition = "opacity 1s ease";
      loginMessage.style.opacity = 0;

      // Remove do DOM após a transição
      setTimeout(() => loginMessage.remove(), 1000);
    }, 5000);
  }
});
