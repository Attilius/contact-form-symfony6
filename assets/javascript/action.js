const contactButton = document.getElementById('contact-btn');
const inputs = document.querySelectorAll('input');

if (contactButton) {
    contactButton.addEventListener("click", () => {
        location.href = "/contact";
    })
}


