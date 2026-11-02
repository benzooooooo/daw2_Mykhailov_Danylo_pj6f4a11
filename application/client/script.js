// JavaScript of the client: only works with the interface of the user

// Goes to another page
function goTo(page) {
    window.location.href = page;
}

// Goes to the page selected in the menu
function goToSelected() {
    let page = document.getElementById("menu").value;
    goTo(page);
}

// Sends the user and the password to the server
function login() {
    let user = document.getElementById("user").value;
    let password = document.getElementById("password").value;

    // Missing data is checked in the client
    if (user == "" || password == "") {
        document.getElementById("message").innerHTML = "You have to write the user and the password.";
        return;
    }

    let data = new FormData();
    data.append("user", user);
    data.append("password", password);

    fetch("../server/login.php", { method: "POST", body: data })
        .then(response => response.text())
        .then(answer => {
            if (answer.trim() == "OK") {
                goTo("menu.html");
            } else {
                alert("Authentication error: the user cannot be validated.");
                goTo("index.html");
            }
        });
}

// Enables or disables the quantity of a product when the checkbox changes
function changeProduct(checkbox, product) {
    let quantity = document.getElementById(product);
    quantity.disabled = !checkbox.checked;
    quantity.required = checkbox.checked;
}

// Sends the order to the server and shows the price with VAT
function sendOrder(event) {
    event.preventDefault();
    let data = new FormData(document.getElementById("orderForm"));

    fetch("../server/createOrder.php", { method: "POST", body: data })
        .then(response => {
            if (response.status == 404) {
                // The server sends an error page: we show it
                response.text().then(page => {
                    document.open();
                    document.write(page);
                    document.close();
                });
            } else {
                response.text().then(price => {
                    document.getElementById("result").innerHTML =
                        "Order saved! Total price (21% VAT included): <strong>" + price + " €</strong>";
                });
            }
        });
}
