<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/my_order">Orders</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Current</li>
                </ol>
            </nav>
        </div>
        <div class="px-0" id="orders-div">
            <!-- <div class="col-12 bg-white p-3 box-shadow">
                <div class="row">
                    <div class="col-xxl-11 col-lg-10 col-md-9">
                        <p class="text-primary"><strong>Order No <span class="text-dark">dfg45df45g45f45ghb45f45dh45</span></strong></p> 
                        <p><small>Placed on</small> </p>
                    </div>
                    <div class="col-xxl-1 col-lg-2 col-md-3 text-end"> Status</div>
                </div>
                <div class="row">
                    <hr>
                    <div class="book-container pb-3">
                        <img src="/image/image-1700742680727.png" alt="Description of your image" class="myorder-img">
                        <div class="book-div1">
                            <p><strong>book name</strong></p>
                            <p><small>Author</small></p>
                            <p><small><i>c</i></small></p>
                        </div>
                        <div class="book-div2">
                            <div class="row">
                                <div class="col-4 text-center"><span class="text-secondary">Price</span><br><span>150</span></div>
                                <div class="col-4 text-center"><span class="text-secondary">Quantity</span><br><span>150</span></div>
                                <div class="col-4 text-center"><span class="text-secondary">TK.</span><br><span>150</span></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div> -->
        </div>
        <!-- <div class="col-12 bg-white p-3 box-shadow">
        </div> -->
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="script/address.js"></script>
<script>
    $(document).ready(function () {
        sidebarActivate('./ongoing_order');
        let userRole = $(userType).attr('usertype');
        if(userRole == 'user'){
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/order/my_order",
                data:JSON.stringify({
                    filters : ['Pending', 'Processing', 'Shipped']
                }),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    let orders = response.order;
                    // console.log(orders);
                    $.map(orders, function (order, indexOrKey) {
                        // console.log(order);
                        if(order.payment){
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
                        }else{
                            $('#orders-div').append(`
                                <div class="col-12 bg-white p-3 box-shadow">
                                    <div class="row">
                                        <div class="col-xxl-11 col-lg-10 col-md-9">
                                            <p class="text-primary"><strong>Order No <span class="text-dark">${order._id}</span></strong></p> 
                                            <p><small>Placed on ${convertUTCToLocalTime(order.createdAt)}</small> </p>
                                        </div>
                                        <div class="col-xxl-1 col-lg-2 col-md-3 text-end">
                                            <a type="button" class="btn btn-outline-danger" href="./order?${order._id}">Payment</a>
                                        <br> ${order.totalPrice} TK.</div>
                                    </div>
                                    <div class="row" id="row${order._id}">
                                        
                                    </div>
                                </div>
                            `);
                        }
                    });

                    $.map(orders, function (order, indexOrKey) {
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
                        })
                        // address
                        if(!order.address){
                            $(`#row${order._id}`).append(addressInput(order._id));
                        }else{
                            $(`#row${order._id}`).append(`<hr><p><strong>Deliver Address : </strong> ${showAddress(order.address)} </p>` );
                        }
                    });
                }
            });
        } else if(userRole == 'library' || userRole == 'seller' || userRole == 'publication' ){
            $.ajax({
                type: "POST",
                // url: "bookhavenapi.sankarsan.xyz/api/v1/order/my_order/vendor",
                url: "bookhavenapi.sankarsan.xyz/api/v1/vendor/currentOrder/",
                data:JSON.stringify({
                    filter :''
                }),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    let books = response.books;
                    $.map(books, function (book, indexOrKey) {
                        console.log(book);
                        var active = '';
                        if (book.quantity < book.orders){
                            active = 'disabled ';
                        } else if (book.orders == 0){
                            active = 'disabled ';
                        }
                        $('#orders-div').append(`
                            <div class="col-12 bg-white p-3 box-shadow">
                                <div class="row">
                                    <div class="book-container pb-3">
                                        <img src="/image/${book.cover}" alt="Cover of image-1700742906450.png" class="myorder-img">
                                        <div class="book-div1">
                                            <p><strong class="bookName" bookId="${book._id}">${book.name}</strong></p>
                                            <p><small>Author: ${book.author}</small></p>
                                            <p><small>Price : ${book.price} TK</small></p>
                                        </div>
                                        <div class="book-div2">
                                            <div class="row">
                                                <div class="col-6 text-center"><span class="text-secondary">Stock:</span> <span id="stock${book._id}">${book.quantity}</span></div>
                                                <div class="col-6 text-center"><span class="text-secondary">Sold:</span> <span id="sold${book._id}">${book.sold}</span></div>
                                                <div class="col-8 pt-2 text-center form-floating">
                                                    <input type="number" class="form-control" id="order${book._id}" placeholder="name@example.com" value="${book.orders}" ${active}>
                                                    <label for="floatingInputValue">Current Order</label>
                                                </div>
                                                <div class="col-4 pt-2">
                                                    <button type="submit" class="btn btn-primary mb-3 confirm_order" bookId="${book._id}" ${active}>Confirm Order</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        `);
                    });
                    
                }
            });
        }else{
            location.replace('/');
        }

        $(document).on('click','.confirm_order', function(e){
            e.preventDefault();
            var bookId = $(this).attr('bookId');
            var stock = $(`#stock${bookId}`).text();
            var sold = $(`#sold${bookId}`).text();
            var order = $(`#order${bookId}`).val();

            var data ={
                    bookId : bookId,
                    count : order
                };
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/vendor/sell",
                data: JSON.stringify(data),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                     $(`#stock${bookId}`).text(stock - order);
                     var s= (sold * 1) + (order * 1) ;
                     $(`#sold${bookId}`).text(s);
                     $(`#order${bookId}`).val('0');
                     showToast(order + ' Books added to sold');
                }
            });
        });
        
        
        setTimeout(hideSpinner, 500);
        // hideSpinner();
    });
</script>
<?php require_once 'script.php'; ?>