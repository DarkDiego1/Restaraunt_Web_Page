let slideIndex = 0;
let slideTimeout;

function showSlides() {
    let slides = document.getElementsByClassName("mySlides");

    for (let i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }

    slideIndex++;
    if (slideIndex > slides.length) {
        slideIndex = 1;
    }

    if (slides[slideIndex - 1]) {
        slides[slideIndex - 1].style.display = "block";
    }
    
    slideTimeout = setTimeout(showSlides, 3000);
}

function renderSlides(slides) {
    const container = document.querySelector(".slideshow-container");
    if (!container || !slides || slides.length === 0) return;
    container.innerHTML = "";
    slides.forEach((slide) => {
        const slideDiv = document.createElement("div");
        slideDiv.className = "mySlides fade";
        slideDiv.innerHTML = `<img src="${slide.img}" alt="${slide.alt || 'Featured Item'}">`;
        container.appendChild(slideDiv);
    });
    slideIndex = 0;
    if (slideTimeout) clearTimeout(slideTimeout);
    showSlides();
}

let Drivers = [];
let Foods = [];
let Drinks = [];
let Slides = [];

function loadMenu() {
    fetch("menu.json")
        .then((response) => {
            if (!response.ok) {
                throw new Error("HTTP error " + response.status);
            }
            return response.json();
        })
        .then((data) => {
            Foods = data.foods || [];
            Drinks = data.drinks || [];
            Drivers = data.drivers || [];
            Slides = data.slides || [];

            if (Slides.length > 0) {
                renderSlides(Slides);
            } else {
                showSlides();
            }

            filterMenu();
        })
        .catch((error) => {
            console.error("Error loading menu.json:", error);
            showSlides();
        });
}

const demoContainer = document.getElementById("demo");
const receiptItemsContainer = document.getElementById("receipt-items");
const totalEl = document.getElementById("receipt-total");
const cashEl = document.getElementById("receipt-cash");
const changeEl = document.getElementById("receipt-change");
const categorySelect = document.getElementById("Category");

const cart = {};

function renderMenu(items) {
    demoContainer.innerHTML = "";
    items.forEach((item) => {
        const card = document.createElement("div");
        card.className = "menu-card";
        card.innerHTML = `
            <img src="${item.img}" alt="${item.name}">
            <p>${item.name}</p>
            <span class="price">$ ${item.price.toFixed(2)}</span>
            <span class="badge">${item.category}</span>
        `;
        card.addEventListener("click", () => addToTicket(item));
        demoContainer.appendChild(card);
    });
}

function filterMenu() {
    const selected = categorySelect.value;
    if (selected === "Food") {
        renderMenu(Foods);
    } else if (selected === "Drinks") {
        renderMenu(Drinks);
    } else if (selected === "All") {
        renderMenu([...Foods, ...Drinks]);
    }
}

function addToTicket(item) {
    if (cart[item.name]) {
        cart[item.name].quantity += 1;
    } else {
        cart[item.name] = {
            name: item.name,
            price: item.price,
            quantity: 1
        };
    }
    updateReceipt();
}

function updateReceipt() {
    receiptItemsContainer.innerHTML = "";
    let total = 0;

    Object.values(cart).forEach((item) => {
        const itemTotal = item.price * item.quantity;
        total += itemTotal;

        const row = document.createElement("div");
        row.className = "receipt-row";
        row.innerHTML = `
            <span>${item.quantity}x ${item.name}</span>
            <span>$ ${itemTotal.toFixed(2).padStart(6, ' ')}</span>
        `;
        receiptItemsContainer.appendChild(row);
    });

    const cash = total > 0 ? Math.ceil(total / 50) * 50 : 0;
    const change = cash > 0 ? cash - total : 0;

    totalEl.textContent = `$ ${total.toFixed(2)}`;
    cashEl.textContent = `$ ${cash.toFixed(2)}`;
    changeEl.textContent = `$ ${change.toFixed(2)}`;
}

function handlePayment(event) {
    const cartItems = Object.values(cart);
    if (cartItems.length === 0) {
        event.preventDefault();
        alert("Please add at least one item to your order before checkout!");
        return false;
    }

    let total = 0;
    cartItems.forEach((item) => {
        total += item.price * item.quantity;
    });

    document.getElementById("cart_data").value = JSON.stringify(cartItems);
    document.getElementById("order_total").value = total.toFixed(2);
    return true;
}

loadMenu();
