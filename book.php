<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/#">Book</a></li>
                    <li class="breadcrumb-item active" aria-current="page" id="navBook"></li>
                </ol>
            </nav>
        </div>
        <div class="col-8 bg-white p-3 box-shadow">
            <div class="book-container">
                
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="book-div5 bg-white p-3 box-shadow">
                <center> <strong>Review</strong></center>
            </div>
        </div>
        <!-- <div class="col-12 bg-white p-3 box-shadow">
        </div> -->
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        // Get the current URL
        var currentURL = window.location.href;
        var index = currentURL.indexOf('?');
        var bookId = index !== -1 ? currentURL.substring(index + 1) : '';
        console.log(bookId);
        var rating=[];
        rating[0]= ``;
        rating[1]= `<i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[2]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[3]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[4]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[5]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>`;


        
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'user'){
            $.ajax({
                type: "POST",
                url: "./api/v1/book/get_detail&review",
                data: JSON.stringify({
                    bookId : bookId
                }),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                    var book = response.book;
                    $(navBook).text(book.name);
                    console.log(book);
                    $('.book-container').append(`
                        <div class="book-div3 center p-3">
                        <img class="book-img" src="/image/${book.cover}" alt="">
                        </div>
                        <div class="book-div4">
                            <div class="row">
                                <div class="name">${book.name}</div>
                                <div class="author"> <strong>Author:</strong> ${book.author}</div>
                                <div class="genre"><strong>Genre:</strong> ${book.genre.name}</div>
                                <div class="publication"><strong>Publication:</strong> ${book.publication}</div>
                                <div class="condition"><strong>Condition:</strong> ${book.condition}</div>
                                <div class="price"><strong>${book.price} TK</strong></div>
                                <div class="price"><strong>Ratings :</strong> ${book.rating} <i class="fa-solid fa-star" style="color: #000000;"></i></div>
                                <div class="seller pt-2"><strong>Seller: </strong> <a href="/user?${book.userId._id}" class="text-dark">${book.userId.name}</a></div>
                                <br>
                                <br>
                                <button type="button" class="col-4 mx-1 btn btn-outline-primary add-cart" bookId="${book._id}">
                                <i class="fa-solid fa-cart-plus"></i>Add to Cart</button>

                                <button type="button" class="col-4 mx-1 btn btn-outline-primary buy-now" bookId="${book._id}">
                                <i class="fa-solid fa-bag-shopping"></i>Buy Now</button>
                            </div>
                        </div>
                    `);
                    if(book.canReview){
                        $('.book-div5').append(`
                            Add your review about the span
                            <br>
                            <i class="fa-regular fa-star " id="star1"></i>
                            <i class="fa-regular fa-star " id="star2"></i>
                            <i class="fa-regular fa-star " id="star3"></i>
                            <i class="fa-regular fa-star " id="star4"></i>
                            <i class="fa-regular fa-star " id="star5"></i>
                            <br>
                            <div class="form-floating mt-2">
                                <textarea class="form-control" placeholder="Leave a comment here" id="comment"></textarea>
                                <label for="floatingTextarea">Comments</label>
                            </div>
                            
                            <button type="button" class="align-self-end mt-2 btn btn-outline-primary" id="btnReview">Post Review</button>
                            <hr>
                            `);
                    }
                    // $('.book-container').append('<div class="book-div5"></div>');
                    $.map(book.reviews, function (review, indexOrKey) {
                        $('.book-div5').append(`
                        <strong class="text-primary">
                            <i class="fa-solid fa-user"></i> ${review.userId.name}
                        </strong><br><b class="px-1"></b>
                        ${rating[review.rating]}
                        <p class="fw-normal p-1">${review.comment}</p>
                        <hr class="mt-1">
                        `);
                    });
                    
                }
            });
        }else {
            $.ajax({
                type: "POST",
                url: "./api/v1/book/get_details",
                data: JSON.stringify({
                    bookId : bookId
                }),
                contentType: "application/json",
                success: function (response) {
                    var book = response.book;
                    
                    $(navBook).text(book.name);
                    console.log(book);
                    $('.book-container').append(`
                        <div class="book-div3 center p-3">
                        <img class="book-img" src="/image/${book.cover}" alt="">
                        </div>
                        <div class="book-div4">
                            <div class="row">
                                <div class="name">${book.name}</div>
                                <div class="author"> <strong>Author:</strong> ${book.author}</div>
                                <div class="genre"><strong>Genre:</strong> ${book.genre.name}</div>
                                <div class="publication"><strong>Publication:</strong> ${book.publication}</div>
                                <div class="condition"><strong>Condition:</strong> ${book.condition}</div>
                                <div class="price"><strong>${book.price} TK</strong></div>
                                <div class="price"><strong>Ratings :</strong> ${book.rating} <i class="fa-solid fa-star" style="color: #000000;"></i></div>
                                <div class="seller pt-2"><strong>Seller: </strong> <a href="/user?${book.userId._id}" class="text-dark">${book.userId.name}</a></div>
                                
                                
                            </div>
                        </div>
                        <div class="book-div"></div>
                    `);
                    $.map(book.reviews, function (review, indexOrKey) {
                        $('.book-div5').append(`
                        <strong class="text-primary">
                            <i class="fa-solid fa-user"></i> ${review.userId.name}
                        </strong><br><b class="px-1"></b>
                        ${rating[review.rating]}
                        <p class="fw-normal p-1">${review.comment}</p>
                        <hr class="mt-1">
                        `);
                    });

                }
            });


            
        }
        $(document).on('click',".fa-star",function (e) { 
            e.preventDefault();
            var id = $(this).attr('id');
            id= id[4];
            for(var i = 1 ; i<= id ;i++){
                $(`#star${i}`).removeClass('fa-regular');
                $(`#star${i}`).addClass('fa-solid');
            }
            for(var i = ++id ; i<= 5 ;i++){
                $(`#star${i}`).removeClass('fa-solid');
                $(`#star${i}`).addClass('fa-regular');
            }
            
        });

        $(document).on('click','#btnReview',function(e){
            e.preventDefault();
            var rate = 0;
           for(var i = 1 ; i<6 ;i++){
                if($(`#star${i}`).hasClass('fa-solid')){
                    rate++;
                }
            } 
            console.log(bookId)
            $.ajax({
                type: "POST",
                url: "./api/v1/book/postReview",
                data: JSON.stringify({
                    bookId : bookId,
                    rating : rate,
                    comment : $(comment).val()
                }),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                    showToast("Review Posted");
                    // location.reload();
                    var userId = current_user();
                    var review = response.review;
                    $('.book-div5').append(`
                        <strong class="text-primary">
                            <i class="fa-solid fa-user"></i> ${userId.name}
                        </strong><br><b class="px-1"></b>
                        ${rating[review.rating]}
                        <p class="fw-normal p-1">${review.comment}</p>
                        <hr class="mt-1">
                        `);
                }
            });
        });

        $(document).on("click",".add-cart",function (e) { 
            e.preventDefault();
            if(!userRole){
                console.log('login first'),
                $(login_pop).removeClass('d-none');
            } else{
                let cart = {
                    bookId : $(this).attr('bookId'),
                    quantity : 1
                }
                $.ajax({
                    type: "POST",
                    url: "./api/v1/auth/cart",
                    headers: {
                        Authorization: 'Bearer '+getCookie('token')
                    },
                    data: JSON.stringify(cart),
                    contentType: "application/json",
                    success: function (response) {
                        console.log(response);
                        $('.cart_count').text(response.cart);
                        showToast("Added to cart.");
                    }
                });
            }
        });


            



        sidebarActivate('./book');
        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>