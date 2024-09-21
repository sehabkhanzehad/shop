async function login() {
    let email = document.getElementById("userEmail").value;
    let password = document.getElementById("userPassword").value;

    if (email == "" && password == "") {
        errorToast("Please enter your email and password.");
    } else if (email == "") {
        errorToast("Please enter your email.");
    } else if (password == "") {
        errorToast("Please enter your password.");
    } else {
        const re =
            /^(?:(?:[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*")|(?:[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-zA-Z0-9!#$%&'*+/=?^_`{|}~-]+)*))@(?:(?:[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?\.)+[a-zA-Z0-9](?:[a-zA-Z0-9-]*[a-zA-Z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)|[a-zA-Z0-9-]*[a-zA-Z0-9]:(?:(?:[ -~]|\\[^\r\n])*)\])$/;
        if (!re.test(email)) {
            errorToast("Please enter a valid email.");
        } else {
            showLoader();
            try {
                let response = await axios.post("/login", {
                    email: email,
                    password: password,
                });
                hideLoader();

                if (response.data.status == "success") {
                    successToast(response.data.message);
                    setTimeout(() => {
                        window.location.href = response.data.url;
                    }, 1000);
                } else {
                    // errorToast(response.data.message);
                    errorToast(response.data["message"]);
                }
            } catch (error) {
                hideLoader();
                errorToast("Something went wrong.");
            }
        }
    }
}

$(document).on("click", ".quickView", async function () {
    // cursoor make pointer

    let product_id = $(this).data("product_id");
    await filluUpQuickView(product_id);
    $("#quickViewModal").modal("show");
});

// $quickView = document.querySelectorAll(".quickView");
// $quickView.forEach((element) => {
//     element.addEventListener("click", function() {
//         let product_id = element.getAttribute("data-product_id");
//         alert(product_id);
//     });
// });

async function filluUpQuickView(id) {
    showLoader();
    const respons = await axios.get("get/product/details", {
        params: {
            productId: id,
        },
    });
    hideLoader();
    // src="{{ asset('assets') }}/images/layout-1/product/a4.jpg"

    // document.getElementById('quickViewImage').src = "{{ asset('assets') }}/images/layout-1/product/" + respons.data.data.thumb_image;

    // document.getElementById("productName").innerHTML = respons.data.data.name;
    // show name in 30 character
    let name = respons.data.data.name;
    document.getElementById("productName").innerHTML =
        name.length > 30 ? name.substring(0, 30) + "..." : name;
    document.getElementById("quickViewImage").src =
        "/uploads/custom-images2/" + respons.data.data.thumb_image;

    let price = respons.data.data.price;
    let offer_price = respons.data.data.offer_price;

    if (offer_price == null) {
        document.getElementById("productPrice").innerHTML = "৳ " + price;
    } else {
        document.getElementById("productPrice").innerHTML = "৳ " + offer_price;
    }

    document.getElementById("ProductDetails").innerHTML =
        respons.data.data.long_description;
}

// $(function () {
//     $(document).on("click", ".add-to-cart", function (e) {
//         let variation_id = $("#size_variation_id").val();
//         let variation_size = $('#size_value').val();
//         let variation_size_id = $('input[name="variation_size_id"]').val();
//         let variation_color = $('#color_value').val();
//         let variation_color_id = $('input[name="variation_color_id"]').val();
//         let variation_price = $('#pro_price').val();
//         var quantity = $('#quantity').val();
//         let image = $('input#pro_img').val();
//         let pro_type = $('input#type').val();

//         let proName = $('input[name="product_name"]').val();
//         let proId = $('input[name="product_id"]').val();
//         let catId = $('input[name="category_id"]').val();

//         window.dataLayer = window.dataLayer || [];

//         dataLayer.push({
//             ecommerce: null
//         });

//         dataLayer.push({
//             event: "add_to_cart",
//             ecommerce: {
//                 currency: "BDT",
//                 value: variation_price,
//                 items: [{
//                     item_id: proId,
//                     item_name: proName,
//                     item_category: catId,
//                     price: variation_price,
//                     quantity: quantity
//                 }]
//             }
//         });

//         let id = $(this).data('id');
//         let url = $(this).data('url');

//         addToCart(url, id, variation_size, variation_color, variation_id, variation_price, quantity,
//             variation_size_id, variation_color_id, image, pro_type, type = "");
//     });

//     function addToCart(url, id, varSize = "", varColor = "", variation_id = "", variation_price = "",
//         quantity, variation_size_id, variation_color_id, image = "", pro_type, type = "") {
//         var csrfToken = $('meta[name="csrf-token"]').attr('content');

//         $.ajax({
//             type: "POST",
//             url: url,
//             headers: {
//                 'X-CSRF-TOKEN': csrfToken
//             },
//             data: {
//                 id,
//                 varSize,
//                 varColor,
//                 variation_id,
//                 variation_price,
//                 quantity,
//                 variation_size_id,
//                 variation_color_id,
//                 image,
//                 pro_type
//             },
//             success: function(res) {

//                 if (res.status) {
//                     toastr.success(res.msg);
//                     if (type) {

//                         if (res.url !== '') {
//                             document.location.href = res.url;
//                         } else {
//                             alert('no');
//                             // Handle specific case
//                         }
//                     } else {
//                         window.location.reload();
//                     }
//                 } else {
//                     // Check if the response contains validation errors
//                     if (res.errors) {
//                         for (var field in res.errors) {
//                             if (res.errors.hasOwnProperty(field)) {
//                                 for (var i = 0; i < res.errors[field].length; i++) {
//                                     toastr.error(res.errors[field][i]);
//                                 }
//                             }
//                         }
//                     } else {
//                         toastr.error(res.msg ||
//                             'An error occurred while processing your request.');
//                     }
//                 }

//             },
//             error: function(xhr, status, error) {
//                 toastr.error('An error occurred while processing your request.');
//             }
//         });
//     }

//     // ... other functions ...
// });

$("#sizes .size a").on("click", function () {
    // Remove active class from all and add to the parent of the clicked element
    $("#sizes .size").removeClass("active");
    $(this).parent().addClass("active");

    // Get the parent <span> of the clicked <a>
    let parentSpan = $(this).closest("span");

    // Retrieve data from the parent <span>
    let value = parentSpan.attr("value"); // 'value' is an attribute, not a data-* attribute
    let varSize = parentSpan.data("varsize");
    let variation_size_id = parentSpan.data("varsizeid");
    let variationPrice = parseFloat(parentSpan.data("varprice"));

    // Display selected size (for example)
    $("#select_size").text("Select Size : " + varSize);

    // Retrieve the current price value from an input field
    var retrieve_price = $('input[id="retrieve_price"]').val();

    // AJAX request to get variation price
    $.ajax({
        type: "get",
        url: "/get-variation_price",
        data: {
            varSize,
            value,
            variationPrice,
            variation_size_id,
        },
        success: function (res) {
            // Update the price display and form inputs
            $(".current-price-product").text("" + res.price);
            $("#size_value").val();
            $("#variation_size_id").val();
            $("#price_val").val(res.price);
            $("#pro_price").val(res.price);

            // Calculate and display the discount
            let retrieve_discount = Number(retrieve_price) - Number(res.price);
            $('input[id="retrieve_discount"]').val(retrieve_discount);
            $("span#dis_amount").text(retrieve_discount);

            console.log(res);
        },
    });

    $("#size_value").val(varSize);
    $("#size_variation_id").val(value);
    $("#variation_size_id").val(variation_size_id);
});


// $(function () {
//     $(document).on("click", ".add-to-cart", async function (e) {
//         let variation_id = $("#size_variation_id").val();
//         let variation_size = $('#size_value').val();
//         let variation_size_id = $('input[name="variation_size_id"]').val();
//         let variation_color = $('#color_value').val();
//         let variation_color_id = $('input[name="variation_color_id"]').val();
//         let variation_price = $('#pro_price').val();
//         var quantity = $('#quantity').val();
//         let image = $('input#pro_img').val();
//         let pro_type = $('input#type').val();

//         let proName = $('input[name="product_name"]').val();
//         let proId = $('input[name="product_id"]').val();
//         let catId = $('input[name="category_id"]').val();

//         window.dataLayer = window.dataLayer || [];

//         dataLayer.push({
//             ecommerce: null
//         });

//         dataLayer.push({
//             event: "add_to_cart",
//             ecommerce: {
//                 currency: "BDT",
//                 value: variation_price,
//                 items: [{
//                     item_id: proId,
//                     item_name: proName,
//                     item_category: catId,
//                     price: variation_price,
//                     quantity: quantity
//                 }]
//             }
//         });

//         let id = $(this).data('id');
//         let url = $(this).data('url');

//         try {
//             await addToCart(url, id, variation_size, variation_color, variation_id, variation_price, quantity,
//                 variation_size_id, variation_color_id, image, pro_type, type = "");
//         } catch (error) {
//             toastr.error('An error occurred while processing your request.');
//         }
//     });

//     async function addToCart(url, id, varSize = "", varColor = "", variation_id = "", variation_price = "",
//         quantity, variation_size_id, variation_color_id, image = "", pro_type, type = "") {

//         var csrfToken = $('meta[name="csrf-token"]').attr('content');

//         try {
//             let response = await axios.post(url, {
//                 id,
//                 varSize,
//                 varColor,
//                 variation_id,
//                 variation_price,
//                 quantity,
//                 variation_size_id,
//                 variation_color_id,
//                 image,
//                 pro_type
//             }, {
//                 headers: {
//                     'X-CSRF-TOKEN': csrfToken
//                 }
//             });

//             if (response.data.status) {
//                 toastr.success(response.data.msg);
//                 if (type) {
//                     if (response.data.url !== '') {
//                         document.location.href = response.data.url;
//                     } else {
//                         alert('no');
//                         // Handle specific case
//                     }
//                 } else {
//                     window.location.reload();
//                 }
//             } else {
//                 if (response.data.errors) {
//                     for (var field in response.data.errors) {
//                         if (response.data.errors.hasOwnProperty(field)) {
//                             for (var i = 0; i < response.data.errors[field].length; i++) {
//                                 toastr.error(response.data.errors[field][i]);
//                             }
//                         }
//                     }
//                 } else {
//                     toastr.error(response.data.msg || 'An error occurred while processing your request.');
//                 }
//             }

//         } catch (error) {
//             toastr.error('An error occurred while processing your request.');
//         }
//     }
// });
