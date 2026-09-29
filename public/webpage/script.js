const form = document.querySelector("form");

if (form) {
    form.addEventListener("submit", async function (event) {

        event.preventDefault();

        const name = document.getElementById("name").value;
        const email = document.getElementById("email").value;
        const message = document.getElementById("message").value;

        const statusMessage = document.getElementById("status-message");

        try {
            const response = await fetch("https://tekbiserv.co.uk/contact", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({
                    name,
                    email,
                    message
                })
            });

            const data = await response.json();

            statusMessage.textContent = data.message;
            statusMessage.style.color = "lightgreen";
            setTimeout(()=>{
                statusMessage.textContent = ""
            }, 5000);

            form.reset();

        } catch (error) {

            statusMessage.textContent =
                "Something went wrong. Please try again.";
            statusMessage.style.color = "red";

        }
    });

}

/* HAMBURGER MENU */

const menuToggle = document.getElementById("menu-toggle");
const navLinks = document.getElementById("nav-links");

menuToggle.addEventListener("click", () => {
    navLinks.classList.toggle("active");
});