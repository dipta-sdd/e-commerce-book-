<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Change password</li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 box-shadow">
            <div class="alert alert-danger d-none" role="alert" id="divAlert">
            </div>
            <div class="col-6">
                <form  >
                    <div class="mb-3">
                    <label for="passwordOld" class="form-label is-invalid">Old Password</label>
                    <input type="password" class="form-control" id="passwordOld" required>
                </div>

                <div class="mb-3">
                    <label for="passwordNew" class="form-label">New Password</label>
                    <input type="password" class="form-control" id="passwordNew" required>
                </div>

                <div class="mb-3">
                    <label for="passwordCon" class="form-label">Confirm Newv Password</label>
                    <input type="password" class="form-control" id="passwordCon" required>
                </div>
                <button type="submit" class="btn btn-primary" id="BtnChangePassword">Submit</button>

                </form>

            </div>
        </div>
        <!-- <div class="col-12 bg-white p-3 box-shadow">
            
        </div>
        <div class="col-12 bg-white p-3 box-shadow"></div> -->
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        sidebarActivate('./change_password');
        
        let userRole = $('#userType').attr('usertype');
        if(!userRole){ location.replace('/');}
        

        $(document).on('click','#BtnChangePassword', function(e){
            e.preventDefault();
            var passOld = $(passwordOld).val();
            var passNew = $(passwordNew).val();
            var passCon = $(passwordCon).val();
            $(divAlert).text("");
            var err = 0;
            if(passOld.length <8){
                $(passwordOld).addClass("is-invalid");
                $(divAlert).text("Too short password.");
                err ++ ;
            } else{
                $(passwordOld).removeClass("is-valid");
            }
            if(passNew.length <8){
                $(passwordNew).addClass("is-invalid");
                $(divAlert).text("Too short password.");
                err++;
            } else{
                $(passwordNew).removeClass("is-valid");
            }
            if(passCon.length <8){
                $(passwordCon).addClass("is-invalid");
                $(divAlert).text("Too short password.");
                err++;
            } else{
                $(passwordCon).removeClass("is-valid");
            }

            if(passNew != passCon){
                $(passwordCon).addClass("is-invalid");
                $(passwordNew).addClass("is-invalid");
                $(divAlert).text("Password doesnot matched.");
                err++;
            }

            var data ={
                    password : passOld,
                    passwordNew : passNew
                };
            if(err == 0){
                $(divAlert).addClass('d-none');
                $.ajax({
                    type: "POST",
                    url: "./api/v1/auth/pass",
                    data: JSON.stringify(data),
                    headers: {
                        Authorization: 'Bearer '+getCookie('token')
                    },
                    contentType: "application/json",
                    success: function (response) {
                        console.log(response);
                        $(divAlert).removeClass('d-none');
                        $(divAlert).removeClass('alert-danger');
                        $(divAlert).addClass('alert-primary');
                        $(divAlert).text("Password Successfully Changed.");
                    }
                });
            }else{
                $(divAlert).removeClass('d-none');
            }
                
        });




        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>