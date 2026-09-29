const paymentButton = document.getElementById("payment-btn");
const API_URL = "https://tekbiserv.co.uk";

paymentButton.addEventListener("click", async function() {

    const params = new URLSearchParams(window.location.search);

    const amount = params.get("amount");

    const response = await fetch(`${API_URL}/donation`, { //need to understand how async await work here??
    method: "POST",
    headers: {
        "Content-Type": "application/json"
    },
    body: JSON.stringify({
        amount: amount
    })
});
const data = await response.json();
window.location.href = data.url; //why and how responsible for redirection??
});