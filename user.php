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
            <div class="">
                <p class="fw-medium fs-5"><i class="fa-solid fa-user pe-2"></i><span id="userName"></span></p>
                <p class="fw-lighter ps-3"><span id="role"></span></p>
                <p class="ps-3"><i>Address: </i><span id="address"></span></p>
                <p class="ps-3"><i?>Raitings: </i><span id="raiting"></span> <i class="fa-solid fa-star" style="color: #000000;"></i></p>
                <p class="ps-3"><i>Total Books: </i><span id="books"></span></p>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="book-div5 bg-white p-3 box-shadow">
                
            </div>
        </div>
        <div class="col-12 bg-white p-3 box-shadow">
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="/script/address.js"></script>
<script>
    $(document).ready(function () {
        var currentURL = window.location.href;
        var index = currentURL.indexOf('?');
        var userId = index !== -1 ? currentURL.substring(index + 1) : '';
        console.log(userId);
        var rating=[];
        rating[0]= `<i class="fa-regular fa-star"><i class="fa-regular fa-star"><i class="fa-regular fa-star"><i class="fa-regular fa-star"><i class="fa-regular fa-star">`;
        rating[1]= `<i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[2]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[3]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[4]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-regular fa-star"></i>`;
        rating[5]= `<i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i><i class="fa-solid fa-star"></i>`;


        
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'user'){
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/get_detail&review",
                data: JSON.stringify({
                    USERID : userId
                }),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {       
                    $(userName).text(response.user.name);
                    $(role).text(response.user.role);
                    $(address).text(showAddress(response.user.address));
                    $(raiting).text(response.averageRating);
                    $(books).text(response.bookCount);

                    $.map(response.user.reviews, function (review, indexOrKey) {
                        $('.book-div5').append(`
                        <strong class="text-primary">
                            <i class="fa-solid fa-user"></i> ${review.userId.name}
                        </strong><br><b class="px-1"></b>
                        ${rating[review.rating]}
                        <p class="fw-normal p-1">${review.comment}</p>
                        <hr class="mt-1">
                        `);
                    });

                    if(response.canReview){
                        $('.book-div5').prepend(`
                            <strong>Add your review about the ${response.user.role}</strong>
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
                }
            });
        }else {
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/get_details",
                data: JSON.stringify({
                    USERID : userId
                }),
                contentType: "application/json",
                success: function (response) {
                    $(userName).text(response.user.name);
                    $(role).text(response.user.role);
                    $(address).text(showAddress(response.user.address));
                    $(raiting).text(response.averageRating);
                    $(books).text(response.bookCount);

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
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/postReview",
                data: JSON.stringify({
                    USERID : userId,
                    rating : rate,
                    comment : $(comment).val()
                }),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: "application/json",
                success: function (response) {
                    showToast("Review Posted");
                }
            });
        });


        sidebarActivate('./user');
        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>