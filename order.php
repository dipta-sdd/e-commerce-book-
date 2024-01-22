<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/#">Order</a></li>
                </ol>
            </nav>
        </div>
        <div class="px-0" id="orders-div">
        </div>
        <!-- <div class="col-12 bg-white p-3 box-shadow">
        </div> -->
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="script/address.js"></script>
<script>
    $(document).ready(function () {
        var currentURL = window.location.href;
        var index = currentURL.indexOf('?');
        var orderId = index !== -1 ? currentURL.substring(index + 1) : '';
        console.log(orderId);
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'user'){
            $.ajax({
                type: "POST",
                url: "./api/v1/order/find",
                data:JSON.stringify({
                    orderId : orderId
                }),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    let order = response.order;
                    $('#orders-div').append(`
                        <div class="col-12 bg-white p-3 box-shadow">
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

                    $.map(order.items, function (bookobj, indexOrKey) {
                        var book = bookobj.bookId;
                        console.log(bookobj);
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
                    });
                    if(!order.payment){
                        $(`#row${order._id}`).append(`
                            <hr>
                            <div class="col-3 paymentRow" >
                                <div class="alert alert-danger" role="alert">
                                    Payment due ${order.totalPrice} TK.
                                </div>
                            </div>
                            <div class="col-3 paymentRow">
                                <div class="form-floating">
                                    <select class="form-select" id="paymentMethod" aria-label="Floating label select example">
                                        <option value="0" selected>Select one</option>
                                        <option value="Bkash">Bkash</option>
                                        <option value="Nagad">Nagad</option>
                                        <option value="Rocket">Rocket</option>
                                    </select>
                                    <label for="paymentMethod">Payment Method</label>
                                </div>
                            </div>
                            <div class="col-3 paymentRow form-floating mb-3 d-none hiden-item">
                                <input type="text" class="form-control" id="transactionID" placeholder="">
                                <label for="transactionID">Transaction ID</label>
                            </div>
                            <div class="col-2 center d-none hiden-item paymentRow">
                                <button type="button" class="btn btn-outline-success" id="btnSubmit" orderId="${order._id}" amount="${order.totalPrice}">
                                <i class="fa-solid fa-money-bill-1-wave"  ></i>Submit</button>
                            </div>
                        `);
                    }
                    if(!order.address){
                        $(`#row${order._id}`).append(addressInput(order._id));
                    }else{
                        $(`#row${order._id}`).append(`<hr><p><strong>Deliver Address : </strong> ${showAddress(order.address)} </p>` );
                    }
                }
            });
        }else{
            location.replace('/');
        }


        $(document).on('change','#paymentMethod', function(e){
            var pament_method = $('#paymentMethod').val();
            if(pament_method != 0){
                $('.hiden-item').removeClass('d-none');
            } else{
                $('.hiden-item').addClass('d-none');
            }
        });

        $(document).on('click','#btnSubmit', function(e){
            e.preventDefault();
            var orderId = $(this).attr('orderId');
            var amount = $(this).attr('amount');
            $.ajax({
                type: "POST",
                url: "./api/v1/order/payment",
                data:JSON.stringify({
                    orderId : orderId,
                    amount : amount,
                    method: $(paymentMethod).val(),
                    transactionID: $(transactionID).val()
                }),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    showToast(response.message);
                    if(response.success){
                        $('.paymentRow').addClass('d-none');
                    }
                    
                }
            });

        });


            



        sidebarActivate('./book');
        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>