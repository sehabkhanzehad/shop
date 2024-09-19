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



$(document).on("click", ".quickView", async function() {
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
            productId: id
        }
    });
    hideLoader();
    // src="{{ asset('assets') }}/images/layout-1/product/a4.jpg"

    // document.getElementById('quickViewImage').src = "{{ asset('assets') }}/images/layout-1/product/" + respons.data.data.thumb_image;

    // document.getElementById("productName").innerHTML = respons.data.data.name;
    // show name in 30 character
    let name = respons.data.data.name;
    document.getElementById("productName").innerHTML = name.length > 30 ? name.substring(0, 30) + "..." : name;
    document.getElementById("quickViewImage").src = "/uploads/custom-images2/" + respons.data.data.thumb_image;

    let price = respons.data.data.price;
    let offer_price = respons.data.data.offer_price;

    if (offer_price == null) {
        document.getElementById("productPrice").innerHTML = "৳ " + price;
    } else {
        document.getElementById("productPrice").innerHTML = "৳ " + offer_price;
    }

    document.getElementById("ProductDetails").innerHTML = respons.data.data.long_description;

}


//   $('#sizes .size').on('click', function() {
//             $('#sizes .size').removeClass('active');
//             $(this).addClass('active');
//             let value = $(this).attr('value');
//             let varSize = $(this).data('varsize');
//             let variation_size_id = $(this).data('varsizeid');
//             //  alert(variation_size_id);
//             $('#select_size').text('Select Size : ' + varSize);

//             var retrieve_price = $('input[id="retrieve_price"]').val();

//             // Assuming you have retrieved the selected variation price somehow
//             let variationPrice = parseFloat($(this).data('varprice'));

//             $.ajax({
//                 type: 'get',
//                 url: '{{ route('front.product.get-variation_price') }}',
//                 data: {
//                     varSize,
//                     value,
//                     variationPrice,
//                     variation_size_id
//                 },
//                 success: function(res) {
//                     $('.current-price-product').text('' + res.price);
//                     $('#size_value').val();
//                     $('#variation_size_id').val();
//                     $('#price_val').val(res.price);
//                     $('#pro_price').val(res.price);

//                     var retrieve_discount = Number(retrieve_price) - Number(res.price);
//                     $('input[id="retrieve_discount"]').val(retrieve_discount);
//                     $('span#dis_amount').text(retrieve_discount);
//                     console.log(res);
//                 }
//             });

//             $("#size_value").val(varSize);
//             $("#size_variation_id").val(value);
//             $("#variation_size_id").val(variation_size_id);
//         });
