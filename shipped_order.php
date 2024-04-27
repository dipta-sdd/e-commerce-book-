<?php require_once 'head.php'; ?>
            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/my_order">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Shipped</li>
                </ol>
            </nav>
        </div>
        <div class="px-0" id="orders-div">

        </div>
        
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="script/address.js"></script>
<script>
    $(document).ready(function () {
        sidebarActivate('./shipped_order');
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'admin'){
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/admin/findOrder",
                data:JSON.stringify({
                    filter :'Shipped'
                }),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    let orders = response.order;
                    console.log(orders);
                    $.map(orders, function (order, indexOrKey) {
                        
                        $('#orders-div').append(`
                            <div class="col-12 bg-white p-3 box-shadow " id="div${order._id}">
                                <div class="row">
                                    <div class="col-xxl-11 col-lg-10 col-md-9">
                                        <p class="text-primary"><strong>Order No <span class="text-dark">${order._id}</span></strong></p> 
                                        <p><small>Placed on ${convertUTCToLocalTime(order.createdAt)}</small> </p>
                                    </div>
                                    <div class="col-xxl-1 col-lg-2 col-md-3 text-end">${order.status} <br> ${order.totalPrice} TK.</div>
                                </div>
                                <div class="row" id="row${order._id}">
                                    
                                </div>
                            </div>
                        `);
                    });

                    $.map(orders, function (order, indexOrKey) {
                        $.map(order.items, function (bookobj, indexOrKey) {
                            var book = bookobj.bookId;
                            $(`#row${order._id}`).append(`
                                <hr>
                                <div class="book-container pb-3">
                                    <img src="/image/${book.cover}" alt="Cover of ${book.cover}" class="myorder-img">
                                    <div class="book-div1">
                                        <p><strong class="bookName" bookId="${book._id}">${book.name}</strong></p>
                                        <p><small>Author: ${book.author}</small></p>
                                        <p><small><i>${book.genre.name}</i></small></p>
                                    </div>
                                    <div class="book-div2">
                                        <div class="row">
                                            <div class="col-4 text-center"><span class="text-secondary">Price</span><br><span>${book.price}</span></div>
                                            <div class="col-4 text-center"><span class="text-secondary">Quantity</span><br><span>${bookobj.quantity}</span></div>
                                            <div class="col-4 text-center"><span class="text-secondary">TK.</span><br><span>${book.price * bookobj.quantity}</span></div>
                                            
                                        </div>
                                    </div>
                                </div>
                            `);
                        })
                        if(order.address){
                            $(`#row${order._id}`).append(`<hr><p><strong>Deliver Address : </strong> ${showAddress(order.address)} </p>` );
                        }
                        $(`#row${order._id}`).append(`
                            <hr class="mt-2">
                            <div class="col-9"></div>
                            <div class="col-3 center">
                                <button type="button" class="ms-auto btn btn-outline-primary delivered" orderId="${order._id}"><i class="fa-solid fa-truck-fast"></i>Delivery Complete</button>
                            </div>
                        ` );
                    });
                }
            });
        }else{
            location.replace('/');
        }

        $(document).on('click','.btn-outline-success', function(e){
            e.preventDefault();
            var orderId = $(this).attr('orderId');

            var data ={
                    orderId: orderId
                };
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/admin/approveOrder",
                data: JSON.stringify(data),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                     console.log(response);
                }
            });
        });

        $(document).on('click','.delivered', function(e){
            e.preventDefault();
            var orderId = $(this).attr('orderId');

            var data ={
                    orderId: orderId,
                    status:"Delivered"
                };
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/admin/orderStatus",
                data: JSON.stringify(data),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                    showToast(response.message);
                    $(`#div${orderId}`).addClass('d-none');
                }
            });
        });
        
        
        setTimeout(hideSpinner, 500);
        // hideSpinner();
    });
</script>
<?php require_once 'script.php'; ?>