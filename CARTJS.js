// Get modal and close elements
const modal = document.getElementById("myModal");
const closeBtn = document.getElementsByClassName("close")[0];

// Populate modal and open it (called dynamically by PHP or manually)
function openModal(orderNumber, totalPrice, authNum, fullName, email) {
    const modalText = document.getElementById("modalText");

    // Populate modal content
    modalText.innerHTML = `
        <strong>Order Confirmation</strong><br>
        Order Number: ${orderNumber}<br>
        Amount: $${totalPrice}<br>
        Authorization Number: ${authNum}<br>
        For: ${fullName}, ${email}
    `;

    // Show the modal
    modal.style.display = "block";
}

// Close modal function
function closeModal() {
    modal.style.display = "none";
}

// Event Listeners
closeBtn.onclick = closeModal; // Close when clicking 'x'
window.onclick = function (event) {
    if (event.target === modal) {
        closeModal();
    }
};

// Button-triggered modal for manual testing (optional)
document.getElementById("Sub").onclick = function () {
    modal.style.display = "block";
};
