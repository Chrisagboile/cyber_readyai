let selectedAmount = null;

const donationButtons = document.querySelectorAll(".donation-btn");

const continueButton = document.getElementById("continue-btn");

const otherAmountButton = document.getElementById("other-amount-btn");
const customAmount = document.getElementById("custom-amount");
const customDonation = document.getElementById("custom-donation");

customAmount.style.display = "none";

donationButtons.forEach(function(button) {
    button.addEventListener("click", function() {
        if (button.id === "other-amount-btn") {
            customAmount.style.display = "block";
            customDonation.focus();
        } else {
            selectedAmount = button.textContent;
            customAmount.style.display = "none";
            console.log("Selected donation:", selectedAmount);
        }
    });
});

customDonation.addEventListener("input", function() { //is there some other way to write this???
    selectedAmount = customDonation.value;
    console.log("Custom donation:", selectedAmount);
});

continueButton.addEventListener("click", function() {

    if (selectedAmount === null || selectedAmount === "") {

        alert("Please select a donation amount before continuing.");

    } else {

        window.location.href =
            "donation-confirmation.html?amount=" +
            encodeURIComponent(selectedAmount);

    }

});