
            </div>


            <!-- login popup -->
            <div class="login-popup d-none" id="login_pop">
                <div class="login-body center">
                    <div class="login-body-2">
                        <div class="row login-body-3">

                            <div class="col-11"></div>
                            <div class="col-1 pb-3" id="login_pop_close_btn"><i class="fa-solid fa-xmark" style="color: #000000;"></i></div>

                            <div class="login-head col-6 p-2" id="login_pop_head">LOGIN</div>
                            <div class="login-head col-6 p-2 login-pop-head-inact" id="signup_pop_head">SIGNUP</div>

                            <div class="col-12 login-div text-start" id="login_div">
                                <div class="form-group pt-2">
                                  <label>Email</label>
                                  <input type="email" class="form-control" name="email" id="login_email" aria-describedby="emailHelpId" placeholder="">
                                    <!-- <small class="text-danger">Please provide a valid city.</small> -->
                                </div>
                                <div class="form-group pt-2">
                                  <label>Password</label>
                                  <input type="password" class="form-control" name="password" id="login_pass" placeholder="">
                                </div>
                                <button type="submit" class="btn btn-primary w-100 mt-3" id="login_submit">LOGIN</button>
                            </div>

                            <div class="col-12 login-div text-start d-none" id="signup_div">
                                <form id="signup_form">
                                    <div class="form-group py-2">
                                        <label>Role</label><label class="text-danger">*</label>
                                        <select class="form-control" name="role" id="signup_role">
                                            <option value="user">Buyer</option>
                                            <option value="seller">seller</option>
                                            <option value="admin">admin</option>
                                            <option value="publication">Publication</option>
                                            <option value="library">Library</option>
                                        </select>
                                    </div>
                                    <div class="form-group pt-2">
                                        <label>Name</label>
                                        <label class="text-danger">*</label>
                                        <input type="text" class="form-control" name="name" id="signup_name" aria-describedby="emailHelpId"
                                            placeholder="Sankarsan Das">
                                        <small class="text-danger" id="signup_name_err"></small>
                                    </div>
                                    <div class="form-group pt-2">
                                        <label>Email</label>
                                        <label class="text-danger">*</label>
                                        <input type="email" class="form-control" name="email" id="signup_email" aria-describedby="emailHelpId" placeholder="email@email.com">
                                        <small class="text-danger" id="signup_email_err"></small>
                                    </div>
                                    <div class="form-group pt-2">
                                        <label>Password</label><label class="text-danger">*</label>
                                        <input type="password" class="form-control" name="password" id="signup_pass" placeholder="********">
                                        <small class="text-danger" id="signup_pass_err"></small>
                                    </div>
                                    <div class="form-group pt-2">
                                        <label>Confirm Password</label><label class="text-danger">*</label>
                                        <input type="password" class="form-control" name="password_c" id="signup_pass_c" placeholder="********">
                                        <small class="text-danger" id="signup_pass_c_err"></small>
                                    </div>
                                    <button type="submit" class="btn btn-primary w-100 mt-3" id="signup_submit">SIGNUP</button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>

        

    </div>
</body>
<script>
    $(document).ready(function () {
        document.getElementById('menu-icon').addEventListener('click', function () {
            const sidebar = document.getElementById('sidebar');
            const content = document.getElementById('content');
            // alert('hi');
            if (sidebar.style.left === "0px") {
                sidebar.style.left = "-200px";
                content.style.marginLeft = window.innerWidth * 0.02+50+"px";
                $('.sidebar').addClass('sidebar_close');
            } else {
                sidebar.style.left = "0";
                content.style.marginLeft = window.innerWidth * 0.02 +250+"px";
                $('.sidebar').removeClass('sidebar_close');
            }
        });
        console.log(current_user());
        let user = current_user();
        if (user) {
            $(nav_profile_btn).html(`
                ${user.name} <span class="badge rounded-pill text-bg-info" id="userType" usertype="${user.role}">${user.role}</span>
            `);
            $(nav_profile_btn).prepend('<i class="fa-solid fa-user pe-2" style="color: #FFFFFF;"></i>');
            // hide login
            $(nav_login_li).addClass('d-none');
            // show profile btn
            $(nav_profile_li).removeClass("d-none");
            if(user.role == 'library' || user.role=='publication')
            {
                activeLibrary();
            } 
            // update cart count
            $('.cart_count').text(user.cartCount);
            // activate sidebar
            loadSidebar(user.role);
        } 
        else {
            // loadSidebar();
            loadSidebar('none');
        }
        











        

        // manage login
        $(nav_login_btn).click(function (e) { 
            e.preventDefault();
            // $(login_pop).removeClass('d-none');
            location.replace("login");
        });
        $(loginBtnSideBar).click(function (e) { 
            e.preventDefault();
            $(login_pop).removeClass('d-none');
        });
        $(login_pop_close_btn).click(function (e) { 
            e.preventDefault();
            $(login_pop).addClass('d-none');
        });
        $(login_submit).click(function (e) { 
            e.preventDefault();
            var data= {
                email: "sdd@gmail.com",
                password: "12345678"
            };
            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/login",
                data: JSON.stringify({
                    email: $(login_email).val(),
                    password: $(login_pass).val()
                }),
                contentType: "application/json",
                success: function (response) {
                    // save token on cookie
                    setCookie("token",response.token,3);
                    location.reload();
                    console.log(response.user);
                    let role= response.user.role;
                    $(nav_profile_btn).html(`
                    ${response.user.name} <span class="badge rounded-pill text-bg-info">${response.user.role}</span>
                      `);
                    $(nav_profile_btn).prepend('<i class="fa-solid fa-user pe-2" style="color: #ffffff;"></i>');
                    // hide login
                    $(nav_login_li).addClass('d-none');
                    // show profile btn
                    $(nav_profile_li).removeClass("d-none");
                    
                    if(role == 'library' || role=='publication')
                    {
                        activeLibrary();
                    }
                    loadSidebar(response.user.role);
                    $(login_pop).addClass('d-none');

                }
            });
        });

        // signup head click
        $(signup_pop_head).click(function (e) { 
            e.preventDefault();
            $(login_div).addClass('d-none ');
            $(signup_div).removeClass('d-none ');
            $(signup_pop_head).removeClass('login-pop-head-inact');
            $(login_pop_head).addClass('login-pop-head-inact');
        });

        // login head click
        $(login_pop_head).click(function (e) {
            e.preventDefault();
            $(signup_div).addClass('d-none ');
            $(login_div).removeClass('d-none ');
            $(login_pop_head).removeClass('login-pop-head-inact');
            $(signup_pop_head).addClass('login-pop-head-inact');
        });

        

        // manage logout
        $(nav_logout_btn).click(function (e) { 
            e.preventDefault();
            deleteCookie('token');
            location.replace('/');
            $(nav_profile_li).addClass('d-none');
            // show profile btn
            $(nav_login_li).removeClass("d-none");
        });
       
        // manage signup
        $(signup_submit).click(function (e) {
            e.preventDefault();
            var pass2= $(signup_pass_c).val();
            var form_data = {
                role: $(signup_role).val(),
                name: $(signup_name).val(),
                email: $(signup_email).val(),
                password: $(signup_pass).val()
            };
            if(form_data.name == ''){
                $(signup_name).addClass('is-invalid');
                $(signup_name_err).text("Please input valid name.");
            } else {
                $(signup_name).removeClass('is-invalid');
                $(signup_name_err).text("");
            }
            if (form_data.email == '') {
                $(signup_email).addClass('is-invalid');
                $(signup_email_err).text("Please input valid email.");
            } else {
                $(signup_email).removeClass('is-invalid');
                $(signup_email_err).text("");
            }
            if (form_data.password == '') {
                $(signup_pass).addClass('is-invalid');
                $(signup_pass_err).text("Please input valid pass.");
            } else {
                $(signup_pass).removeClass('is-invalid');
                $(signup_pass_err).text("");
            }
            if (pass2 == '') {
                $(signup_pass_c).addClass('is-invalid');
                $(signup_pass_c_err).text("Please input valid pass.");
            } else {
                $(signup_pass_c).removeClass('is-invalid');
                $(signup_pass_c_err).text("");
                if (pass2 === form_data.password) {
                    $(signup_pass_c).removeClass('is-invalid');
                    $(signup_pass_c_err).text("");
                } else {
                    $(signup_pass_c).addClass('is-invalid');
                    $(signup_pass_c_err).text("Password didnot match.");
                }
            }

            $.ajax({
                type: "POST",
                url: "https://bookhavenapi.sankarsan.xyz/api/v1/auth/signup",
                data: JSON.stringify(form_data),
                contentType: "application/json",
                success: function (response) {
                    showToast(response.message);
                        console.log('object');
                }
            });

            

            console.log(form_data);
        });



        function activeLibrary(){
            $(nav_myItem_btn).removeClass("d-none");
            $(nav_addItem_btn).removeClass("d-none");
            $(nav_myOrder_btn).addClass("d-none");
        }
        function deactiveLibrary(){
            $(nav_myItem_btn).addClass("d-none");
            $(nav_addItem_btn).addClass("d-none");
            $(nav_myOrder_btn).removeClass("d-none");
        }

        $(document).on('click','.bookName',function(e){
            e.preventDefault();
            var bookId = $(this).attr('bookId');
            location.replace(`book?${bookId}`);
        });




    });
</script>