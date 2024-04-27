<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Withdraw</li>
                </ol>
            </nav>
        </div>
        <div class="col-8 bg-white p-3 box-shadow">
            <div class="">
                <p class="fs-5 fw-semibold font-monospace text-primary">
                    <i class="fa-solid fa-wallet fa-bounce" style="color: #005af5;"></i>
                    Balance : <span id="balance"> 0.00</span> 
                    <button type="button" class="m-3 btn btn-outline-primary" id="btnWidraw"> Withdraw Money </button>
                </p>
                
                <div class="ps-4 hiddenDiv1">
                    Selcet any method:
                    <a type="button" class="m-3 btn btn-outline-primary paymentMethod" value="Bkash"> Bkash </a>
                    <a type="button" class="m-3 btn btn-outline-primary paymentMethod" value="Nagad"> Nagad </a>
                    <a type="button" class="m-3 btn btn-outline-primary paymentMethod" value="Rocket"> Rocket </a>
                    <div class="row hiddenDiv">
                        <div class="col-6 ">
                            <form class="form-floating" >
                                <input type="text" class="form-control" id="accountNumber" placeholder="01000000000" value="">
                                <label for="floatingInputValue">Enetr your account number</label>
                            </form>
                        </div>
                        <div class="col-3">
                            <form class="form-floating hidden" >
                                <input type="text" class="form-control" id="amount" placeholder="0.00" value="">
                                <label for="floatingInputValue">Enetr amount</label>
                            </form>
                        </div>
                        <div class="col-3 center">
                            <a type="button" class="btn btn-outline-primary" id="withdrawRequest"> Request Withdraw </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-4 mb-2">
            <div class="book-div5 bg-white p-3 box-shadow">
                <p><center><strong>Transaction History</strong></center></p>
                <!-- <small class="text-danger"> Sorry no tran</small> -->
            </div>
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        sidebarActivate('./withdraw');
        // ${convertUTCToLocalTime(order.createdAt)}
        $('.hiddenDiv1').hide();
        $('.hiddenDiv').hide();
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'library' || userRole == 'seller' || userRole == 'publication'){
            console.log(userRole);
        } else{
            
            location.replace('./');
        }
        var user = current_user();
        $(balance).text(user.balance);
        $(btnWidraw).click(function (e) { 
            e.preventDefault();
            $('.hiddenDiv1').fadeIn();
        });

        var paymentMethod = '';
        $('.paymentMethod').on('click', function() {
            // $('.paymentMethod').removeClass('disabled');        
            $(this).siblings('.paymentMethod').addClass('disabled');
            paymentMethod = $(this).attr('value');
            $('.hiddenDiv').fadeIn();
        });

        $(withdrawRequest).click(function (e) { 
            e.preventDefault();
            var data={
                method: paymentMethod,
                ac: $(accountNumber).val(),
                amount: $(amount).val(),
            }
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/transaction/request",
                data: JSON.stringify(data),
                contentType: "application/json",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                success: function (response) {
                    showToast(response.message);
                }
            });
        });

        var statusarr={
            'Pending': '<span class="text-primary">Pending</span>',
            'Canceled':'<span class="text-danger">Canceled</span>',
            'Approved':'<span class="text-success">Approved</span>'
        }


        $.ajax({
            type: "POST",
            url: "https://bookhavenapi.sankarsan.xyz/api/v1/transaction/history",
            contentType: "application/json",
            headers: {
                Authorization: 'Bearer '+getCookie('token')
            },
            success: function (response) {
                console.log(response);
                $.map(response.trans, function (tran, indexOrKey) {
                    $('.book-div5').append(`
                        <div>
                            <hr>
                            <p><i class="fa-solid fa-file-invoice"></i> <span class="fw-semibold">${tran.amount} TAKA</span></p>
                            <p class="fw-lighte ps-4"><small>${tran.method}</small></p>
                            <p class="fw-lighte ps-4"><small>${convertUTCToLocalTime(tran.createdAt)}</small></p>
                            <p class="ps-4">Status: ${statusarr[tran.status]}</p>
                            
                        </div>
                    `);
                });
            }
        });


        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>