<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Cart</li>
                </ol>
            </nav>
        </div>
        <div class="col-12">
            <div class="row" id="cartContainer">
                        
            </div>
                        
            
        </div>
        <div class="col-12 bg-white p-3 box-shadow">
            <div class="row">
                <div class="col-11">
                    Toatal Books : <span id="spanTotal">0</span> Pcs <hr class="m-1">
                    Toatal Price : <span id="spanPrice">0</span> TK. <hr class=" m-1">
                </div>
                <div class="col-1">
                    <button type="button" class="btn btn-outline-primary" id="checkOut">Chekout</button>
                </div>
            </div>
            
            
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        sidebarActivate('./cart');
        let userRole = $(userType).attr('usertype');
        if(userRole != 'user'){
            location.replace('/');
        }
        // load cart
        $.ajax({
            type: "POST",
            url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/cart_list",
            headers: {
                Authorization: 'Bearer '+getCookie('token')
            },
            success: function (response) {
                let i = 1;
                let margin= {};
                margin[1] = 'pe-1';
                margin[0] = 'ps-1';
                cart=response.cart.books;
                $.map(cart, function (book, indexOrKey) {
                    var r = i%2;
                    console.log(book);
                    $(cartContainer).append(`
                        <div class="col-6 px-0 ${margin[r]}"  id="cart${book.bookId._id}">
                            <div class="cart-row box-shadow p-2">
                                <input class="form-check-input me-3 cart-check-box" type="checkbox" value="" bookId="${book.bookId._id}">
                                <img class="cart-img me-3" src="image/${book.bookId.cover}" alt="cover">
                                <div class="cart-col me-3" style="width:85%">
                                    <b class="bookName" bookId="${book.bookId._id}">${book.bookId.name}</b>
                                    <small>Aurthor: ${book.bookId.author} </small>
                                    <i><small> ${book.bookId.genre.name}</small></i>
                                    <hr>
                                    <p > <span id="price${book.bookId._id}">${book.bookId.price} </span> TK.</p>
                                </div>
                                <div class="cart-col w-25 mx-auto ">
                                    <div class="center">
                                        <a class="cart-remove" bookId="${book.bookId._id}"> - </a>
                                        <p class="px-2 count" id="count${book.bookId._id}"> ${book.quantity}</p>
                                        <a class="cart-add" bookId="${book.bookId._id}"> + </a>
                                    </div>
                                    <p class="center">TK <span id="total_price${book.bookId._id}"> ${book.bookId.price *  book.quantity} </span></p>
                                    <p class="center cart-delete" bookId="${book.bookId._id}"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
                                        <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H2.506a.58.58 0 0 0-.01 0H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1h-.995a.59.59 0 0 0-.01 0zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47ZM8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
                                    </svg></p>
                                    
                                </div>
                            </div>
                        </div> 
                    `);
                    i = i + 1;
                });
            }
        });
        
        // click add
        $(document).on("click",".cart-add",function (e) { 
            e.preventDefault();
            let cart = {
                bookId : $(this).attr('bookId'),
                quantity : 1
            }
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/cart",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify(cart),
                contentType: "application/json",
                success: function (response) {
                    $('.cart_count').text(response.cart);
                    var bookId = response.bookId;
                    let price = $(`#price${bookId}`).text();
                    let count = $(`#count${bookId}`).text();
                    let total_price = $(`#total_price${bookId}`).text();
                    console.log(price);
                    console.log(count);
                    console.log(total_price);
                    count++;
                    total_price = price*count;
                    $(`#count${bookId}`).text(count);
                    $(`#total_price${bookId}`).text(total_price);
                    calcCart();
                }
            });
            
        });

        // click remove
        $(document).on("click",".cart-remove",function (e) { 
            e.preventDefault();
            let cart = {
                bookId : $(this).attr('bookId'),
                quantity : -1
            }
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/cart",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify(cart),
                contentType: "application/json",
                success: function (response) {
                    $('.cart_count').text(response.cart);
                    var bookId = response.bookId;
                    let price = $(`#price${bookId}`).text();
                    let count = $(`#count${bookId}`).text();
                    let total_price = $(`#total_price${bookId}`).text();
                    count--;
                    total_price = price*count;
                    $(`#count${bookId}`).text(count);
                    $(`#total_price${bookId}`).text(total_price);
                    if(count == 0){
                        $(`#cart${bookId}`).addClass('d-none');
                    }
                    calcCart();
                }
            });
            
        });

        // click delete
        $(document).on("click",".cart-delete",function (e) { 
            e.preventDefault();
            let bookId = $(this).attr('bookId');
            let quantity = $(`#count${bookId}`).text();
            quantity = 0 - quantity ;

            let cart = {
                bookId : $(this).attr('bookId'),
                quantity : quantity
            }
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/cart",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify(cart),
                contentType: "application/json",
                success: function (response) {
                    $('.cart_count').text(response.cart);
                    var bookId = response.bookId;
                    let price = $(`#price${bookId}`).text();
                    let count = $(`#count${bookId}`).text();
                    let total_price = $(`#total_price${bookId}`).text();
                    count =0;
                    total_price = price*count;
                    $(`#count${bookId}`).text(count);
                    $(`#total_price${bookId}`).text(total_price);
                    if(count == 0){
                        $(`#cart${bookId}`).addClass('d-none');
                    }
                    calcCart();
                }
            });
            
        });

        // check box chnage
        $(document).on("change",".cart-check-box",function (e) { 
            e.preventDefault();
            calcCart();
        });
        // click check out
        $(checkOut).click(function (e) { 
            e.preventDefault();
            const checkBoxes = document.querySelectorAll('.cart-check-box');
            const checkBoxArray = Array.from(checkBoxes);
            const checkedCheckBoxes = checkBoxArray.filter(checkbox => checkbox.checked);
            let order = checkedCheckBoxes.map((checkbox, index) => {
                let id = checkbox.getAttribute('bookId');
                return {
                    bookId : checkbox.getAttribute('bookId'),
                    quantity : $(`#count${checkbox.getAttribute('bookId')}`).text()
                }
            });
            // console.log(order);
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/order/checkout",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify({
                    order : order
                }),
                contentType: "application/json",
                success: function (response) {
                    console.log(response);
                    location.replace(`./order?${response.order._id}`);
                }
            });

        });


        function calcCart(){
            const checkBoxes = document.querySelectorAll('.cart-check-box');
            const checkBoxArray = Array.from(checkBoxes);
            let total = 0;
            let totalCount = 0;
            checkBoxArray.map((checkbox, index) => {
                let id = checkbox.getAttribute('bookId');
                if(checkbox.checked){
                    let total_price = $(`#total_price${id}`).text();
                    let count = $(`#count${id}`).text();
                    total=(total * 1) + 1 * total_price;
                    totalCount=(totalCount * 1) + 1 * count;
                }
            });
            $(spanTotal).text(totalCount);
            $(spanPrice).text(total);
        }
        calcCart();
        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>