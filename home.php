<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Book</li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 box-shadow">
            <div class="row">
                <div class="col-4">
                    <form class="d-flex flex-row" role="search">
                        <input class="form-control me-2 inputSearchBook" type="search" placeholder="Book Name" aria-label="Search">
                        <button class="btn btn-outline-success btnSearchBook" type="submit">Search</button>
                    </form>
                </div>
                <div class="col-2">
                    <select class="form-select" aria-label="Default select example" id="selectCondition">
                        <option value="both" selected>Filter Condition</option>
                        <option value="new">New</option>
                        <option value="old">Old</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="col-12 bg-white p-3 box-shadow">
            <div class="books-card-container">
                
            </div>
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'seller' ||userRole == 'library' ||userRole == 'publication' ||userRole == 'admin' ){
            location.replace('./dashboard');
        }
        
        loadBooks();
        function loadBooks (){
            var formdata ={
                name: $('.inputSearchBook').val(),
                condition: $(selectCondition).val()
            };
            $.ajax({
                type: "POST",
                url: "./api/v1/book/search",
                data: JSON.stringify(formdata),
                contentType: "application/json",
                success: function (response) {
                    console.log(response);
                    $('.books-card-container').html(``);
                    $.map(response.books, function (book, indexOrKey) {
                        $('.books-card-container').append(createBookGrid(book));
                    });
                    
                }
            });
        }


        $('.btnSearchBook').click(function (e) { 
            e.preventDefault();
            loadBooks();
        });
        $(selectCondition).change(function (e) { 
            e.preventDefault();
            loadBooks();
        });
            
        
        



        function createBookGrid(book){
            book.cover=book.cover.replace("uploads\\",'');
            return `
                <div class='grid-item '>
                    <div class="card-container">
                        <div class="img-container">
                            <img src="./image/${book.cover}" alt="" class="book-cover-img" />
                        </div>
                        <p class="book book-name bookName" bookId="${book._id}">${book.name}</p>
                        <p class="book book-author"><i><u>Author</u> :</i> ${book.author}</p>
                        <p class="book book-genre"><i><u>Genre</u> :</i> ${book.genre.name}</p>
                        <p class="book book-price"><i><u>TK</u> .</i> ${book.price}</p>
                        <button class='btn-add-cart mt-auto' bookId="${book._id}">
                            <i class="fa-solid fa-cart-plus"></i>
                            Add to Cart
                        </button>
                        <button class='btn-buy'  bookId="${book._id}">
                            <i class="fa-solid fa-bag-shopping"></i>
                            Buy Now
                        </button>


                    </div>
                </div>
            `;
        }

        let user = current_user();
        sidebarActivate('./tmp');
        hideSpinner();

        // on click add to cart
        $(document).on("click",".btn-add-cart",function (e) { 
            e.preventDefault();
            if(!user){
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

        $(document).on("click",".btn-buy",function (e) { 
            e.preventDefault();
            if(!user){
                console.log('login first'),
                $(login_pop).removeClass('d-none');
            } else{
                let cart ={
                    bookId : $(this).attr('bookId'),
                    quantity : 1
                };
                $.ajax({
                    type: "POST",
                    url: "./api/v1/order/checkout2",
                    headers: {
                        Authorization: 'Bearer '+getCookie('token')
                    },
                    data: JSON.stringify({order:[ cart]}),
                    contentType: "application/json",
                    success: function (response) {
                        console.log(response);
                    location.replace(`./order?${response.order._id}`);
                    }
                });
            }
        });
    });
</script>
<?php require_once 'script.php'; ?>