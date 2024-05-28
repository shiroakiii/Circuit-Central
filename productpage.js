$(document).ready(function() {
    fetchProducts();
});

function fetchProducts() {
    $.ajax({
        type: "GET",
        url: 'http://localhost/Circuit%20Central/getProducts.php',
        success: function (response) {
            console.log("Raw Response:", response); // Log the raw response
            var products = JSON.parse(response);
            populateProducts(products);
        },
        error: function (error) {
            console.error("Error fetching products: ", error);
        }
    });
}

function populateProducts(products) {
    var productsContainer = document.getElementById('productsContainer');

    products.forEach(function(product) {
        var cardDiv = document.createElement('div');
        cardDiv.className = 'card mb-3';
        cardDiv.style.maxWidth = '540px';

        var rowDiv = document.createElement('div');
        rowDiv.className = 'row g-0';

        var imageColDiv = document.createElement('div');
        imageColDiv.className = 'col-md-4';

        var productImage = document.createElement('img');
        productImage.src = product.ImageURL;
        productImage.className = 'img-fluid rounded-start';
        productImage.alt = product.ProductName;

        imageColDiv.appendChild(productImage);

        var contentColDiv = document.createElement('div');
        contentColDiv.className = 'col-md-8';

        var cardBodyDiv = document.createElement('div');
        cardBodyDiv.className = 'card-body';

        var titleH5 = document.createElement('h5');
        titleH5.className = 'card-title';
        titleH5.textContent = product.ProductName;

        var descP = document.createElement('p');
        descP.className = 'card-text';
        descP.textContent = product.ProductDesc;

        var updatedP = document.createElement('p');
        updatedP.className = 'card-text';
        updatedP.innerHTML = '<small class="text-muted">Last updated 3 mins ago</small>';

        cardBodyDiv.appendChild(titleH5);
        cardBodyDiv.appendChild(descP);
        cardBodyDiv.appendChild(updatedP);

        contentColDiv.appendChild(cardBodyDiv);

        rowDiv.appendChild(imageColDiv);
        rowDiv.appendChild(contentColDiv);

        cardDiv.appendChild(rowDiv);

        productsContainer.appendChild(cardDiv);
    });
}
