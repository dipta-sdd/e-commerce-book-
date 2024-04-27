<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Edit</li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 rounded">
            <div class="table-responsive">
                <table class="table w-100">
                    <tbody>
                        <tr>
                            <td scope="row"><b>Name</b></td><td><b>:</b></td>
                            <td class="w-100">
                              <input type="text" class="form-control text-capitalize" name="" id="profileName" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Email</b></td><td><b>:</b></td>
                            <td id="">
                                <input type="email" class="form-control" name="" id="profileEmail" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Phone</b></td><td><b>:</b></td>
                            <td id="">
                                <input type="text" class="form-control text-capitalize" name="" id="profilePhone" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Address</b></td><td><b>:</b></td>
                            <td id="profileAddress">
                                <input type="text" class="form-control text-capitalize" name="" id="profileAddress" >
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <a class="btn btn-primary btn-sm " id="btnUpdate" role="button">Save Update</a>

        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="script/address.js"></script>
<script>
    $(document).ready(function () {
        sidebarActivate('./edit_profile');
        let userRole = $('#userType').attr('usertype');
        if(!userRole){ location.replace('/');}

        let user = current_user();
        $(profileName).val(user.name);
        $(profileEmail).val(user.email);
        $(profilePhone).val(user.phone);
        $(profileAddress).html(addressInputProfile(user._id));
        if(user.address){
           showAddressProfile(user._id , user.address); 
        }

        // click save update
        $(btnUpdate).click(function (e) { 
            e.preventDefault();
            var data = {
                name: $(profileName).val(),
                email: $(profileEmail).val(),
                phone: $(profilePhone).val(),
                address: getAddress(user._id)
            };
            $.ajax({
                type: "POST",
                url: "bookhavenapi.sankarsan.xyz/api/v1/auth/update-user",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify(data),
                contentType: "application/json",
                success: function (response) {
                    showToast("Update Successful.");
                    
                }
            });
        });
                hideSpinner();
    });
</script>
<?php require_once 'script.php'; ?>