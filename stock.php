<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Stock</li>
                </ol>
            </nav>
            
        </div>
        <div class="col-12 bg-white p-3 box-shadow">

            <nav class="navbar bg-body-tertiary mb-2">
                <div class="container-fluid">
                    <form class="d-flex" role="search">
                    <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" id="name">
                    <button class="btn btn-outline-success" type="submit" id="btnsearch">Search</button>
                    </form>
                </div>
            </nav>

            <table class="table table-striped table-bordered ">
                <thead>
                    <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Author</th>
                    <th scope="col">Condition</th>
                    <th scope="col">Stock</th>
                    <th scope="col">Sold</th>
                    <th scope="col">Order</th>
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
        
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'seller' || userRole == 'library' || userRole == 'publication' ){
           console.log(userRole);
        }else{
             location.replace('/');
        }
        loadBooks();
        $(btnsearch).click(function (e) { 
            e.preventDefault();
            loadBooks();
        });
        $('#name').keyup(function (e) { 
            e.preventDefault();
            loadBooks();
        });


        function loadBooks(){
            var formdata = {
                name: $('#name').val()
            }
            $.ajax({
                type: "POST",
                url: "./api/v1/book/stock",
                headers: {
                    Authorization: 'Bearer '+getCookie('token')
                },
                data: JSON.stringify(formdata),
                contentType: "application/json",
                success: function (response) {
                    console.log(response);
                    $('tbody').html('');
                    $.map(response.books, function (book, index) {
                        book.cover= book.cover.replace("uploads\\", "");
                        
                        $('tbody').append(`
                            <tr   bookId="${book._id}">
                            <th scope="row">${index+1}</th>
                            <td class="bookName">${book.name}</td>
                            <td>${book.author}</td>
                            <td>${book.condition}</td>
                            <td>${book.quantity}</td>
                            <td>${book.sold}</td>
                            <td>${book.orders}</td>
                            <td><a href="./edit_book?${book._id}"><i class="fa-solid fa-pen-to-square" style="color: #005eff;"></i></a></td>
                            </tr>
                        `);
                    });
                }
            });
        }
        sidebarActivate('./stock');
        hideSpinner();
    });
</script>
<?php require_once 'script.php'; ?>