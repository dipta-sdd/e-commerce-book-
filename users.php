<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page"><a href="/users">Users</a></li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 box-shadow">
            <nav class="navbar bg-body-tertiary mb-2">
                <div class="container-fluid">
                    <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="name">
                    <button class="btn btn-outline-success" type="submit" id="btnsearch">Search</button>
                    <select class=" ms-3 form-select" aria-label="Default select example" id="selectCondition">
                        <option value="all" selected>Filter By Role</option>
                        <option value="user">User</option>
                        <option value="seller">Seller</option>
                        <option value="library">Library </option>
                        <option value="publication">Publication </option>
                        <option value="admin">Admin </option>
                    </select>    
                </form>
                </div>
                
            </nav>
            <table class="table table-striped table-bordered ">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Role</th>
                    <th scope="col">Status</th>
                    <th scope="col"></th>
                    </tr>
                </thead>
                <tbody class="">

                </tbody>
            </table>
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        sidebarActivate('./users');
        let userRole = $('#userType').attr('usertype');
        if(userRole != 'admin'){
            location.replace('/');
        }

        var role ={
            user:'<option value="user" selected>User</option><option value="seller">Seller</option><option value="library">Library</option><option value="publication">Publication</option>',
            seller:'<option value="user">User</option><option value="seller" selected>Seller</option><option value="library">Library</option><option value="publication">Publication</option>',
            library:'<option value="user" >User</option><option value="seller">Seller</option><option value="library" selected>Library</option><option value="publication">Publication</option>',
            publication:'<option value="user">User</option><option value="seller">Seller</option><option value="library">Library</option><option value="publication" selected>Publication</option>',
            admin: '<option value="admin" selected>Admin</option>'
        }
        var status = {
            approved:'<option value="approved" selected>Approved</option><option value="pending">Pending </option><option value="blobked">Blobked </option>',
            pending:'<option value="approved">Approved</option><option value="pending" selected>Pending </option><option value="blobked">Blobked </option>',
            blobked:'<option value="approved">Approved</option><option value="pending">Pending </option><option value="blobked" selected>Blobked </option>'
        }
        loadUsers();
        $(btnsearch).click(function (e) { 
            e.preventDefault();
            loadUsers();
        });
        $(selectCondition).change(function (e) { 
            e.preventDefault();
            loadUsers();
        });
        function loadUsers(){
            $.ajax({
                type: "POST",
                url: "./api/v1/admin/users",
                data: JSON.stringify({
                    name: $('#name').val(),
                    filter:$(selectCondition).val()
                }),
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                contentType: 'application/json',
                success: function (response) {
                    console.log(response);
                    var index= 0;
                    $('tbody').html('');
                    $.map(response.users, function (user, index) {
                        $('tbody').append(`
                            <tr class="">
                            <th scope="row">${index+1}</th>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>
                                <select class="form-select form-select-sm" aria-label="Small select example" id="role${user._id}">
                                    ${role[user.role]}
                                </select>
                            </td>
                            <td>
                                <select class="form-select form-select-sm" aria-label="Small select example" id="status${user._id}">
                                    ${status[user.status]}
                                </select>
                            </td>
                            <td>
                                <button type="button" class="ms-auto btn btn-outline-danger btn-sm">Update</button>
                            </td>
                            </tr>
                        `);
                    });
                }
            });
        }



        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>