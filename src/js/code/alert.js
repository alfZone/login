/**
 * @author alf
 * @copyright 2022
 * @ver 1.0
 * https://github.com/alfZone/htmlJS
 */

// Função para exibir mensagens de alerta
function showAlert(message, type) {
  const alertDiv = document.getElementById("alertMessage");
  alertDiv.className = `alert-message alert-${type}`;
  alertDiv.innerHTML = `<i class="bi bi-${type === "success" ? "check-circle" : type === "info" ? "info-circle" : "exclamation-triangle"} me-2"></i>${message}`;
  alertDiv.style.display = "block";

  setTimeout(() => {
    alertDiv.style.display = "none";
  }, 5000);
}
