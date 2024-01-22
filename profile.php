<?php require_once 'head.php'; ?>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">Profile</li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 rounded">
            <div class="table-responsive">
                <table class="table w-100">
                    <tbody>
                        <tr>
                            <td scope="row"><b>Name</b></td><td><b>:</b></td>
                            <td class="w-100" id="profileName"> </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Email</b></td><td><b>:</b></td>
                            <td id="profileEmail"> </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Phone</b></td><td><b>:</b></td>
                            <td id="profilePhone"> </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Address</b></td><td><b>:</b></td>
                            <td id="profileAddress"> </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <a class="btn btn-primary btn-sm " href="/edit_profile" role="button">Edit Profile</a>

        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script src="script/address.js"></script>
<script>
    $(document).ready(function () {
        sidebarActivate('./profile');
        let userRole = $('#userType').attr('usertype');
        if(!userRole){ location.replace('/');}
        hideSpinner();
        let user = current_user();
        $(profileName).text(user.name);
        $(profileEmail).text(user.email);
        $(profilePhone).text(user.phone);
        $(profileAddress).text(showAddress(user.address));
        
    });
</script>
<?php require_once 'script.php'; ?>