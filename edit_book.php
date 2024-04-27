<?php require_once 'head.php'; ?>

            <nav aria-label="breadcrumb" class="">
                <ol class="breadcrumb p-3">
                    <li class="breadcrumb-item"><a href="/"><i class="fa-solid fa-house" style="color: #005eff;"></i></a></li>
                    <li class="breadcrumb-item" aria-current="page"><a href="/profile">Profile</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Add Book</li>
                </ol>
            </nav>
        </div>
        <div class="col-12 bg-white p-3 rounded">
            <form id="addItem" enctype="multipart/form-data">
            <div class="table-responsive">
                <table class="table w-100">
                    <tbody>
                        <small class="text-danger d-none" id="err">Please fill all * marked field.</small>
                        <tr>
                            <td scope="row" style="min-width: 120px"><b> Cover</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td class="w-100" id="tdImage">
                              <input type="file" id="cover" accept="image/*">
                                <br> 
                                <div style="max-width: 500px">
                                    <img id="coverView" src="#" alt="Preview Image" style="display: none; max-height: 400px;">
                                    <br>
                                    <!-- <button id="cropButton" style="display: none;">Crop Image</button> -->
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row" style="min-width: 120px"><b>Title</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td class="w-100">
                              <input type="text" class="form-control" name="" id="bookName" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Price</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                <input type="number" class="form-control" name="" id="bookPrice" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Genre</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                  <select class="form-control" name="" id="bookGenre">
                                    <option value="" selected> Choose One....</option>
                                  </select>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Author</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                <input type="text" class="form-control" name="" id="bookAuthor" >
                            </td>
                        </tr>
                        
                        <tr>
                            <td scope="row"><b>Publication</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                <input type="text" class="form-control" name="" id="bookPublication" >
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Condition</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                <select class="form-control" name="" id="bookCondition">
                                    <option value="" selected> Choose One....</option>
                                    <option value="new" > New</option>
                                    <option value="old" > Old</option>
                                  </select>
                            </td>
                        </tr>
                        <tr>
                            <td scope="row"><b>Quantity</b><label class="text-danger">*</label></td><td><b>:</b></td>
                            <td>
                                <input type="number" class="form-control" name="" id="bookQuantity" >
                            </td>
                        </tr>
                        
                    </tbody>
                </table>
            </div>
            </form>
            <a class="btn btn-primary btn-sm " id="btnEditBook" role="button">Edii Book</a>
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>
<script>
    $(document).ready(function () {
        // sidebarActivate('./');
        var currentURL = window.location.href;
        var index = currentURL.indexOf('?');
        var bookId = index !== -1 ? currentURL.substring(index + 1) : '';
        console.log(bookId);
        
        let userRole = $('#userType').attr('usertype');
        if(userRole == 'seller' || userRole == 'library' || userRole == 'publication' ){ 
            console.log(userRole);
        }else{
            location.replace('/');
        }
        
        // manage genre
        $.ajax({ url: 'https://bookhavenapi.sankarsan.xyz/api/v1/book/genre', method: 'GET' }).done(function (response) {
            var genres = response.genres;
            $.map(genres, function (genre, indexOrKey) {
                $(bookGenre).append(`
                    <option value="${genre._id}">${genre.name}</option>
                `);
            });
        });
        // preview
        $.ajax({
            type: "POST",
            url: "https://bookhavenapi.sankarsan.xyz/api/v1/book/get_details",
            data: JSON.stringify({
                bookId : bookId
            }),
            headers: {
                Authorization: 'Bearer '+getCookie('token')
            },
            contentType: "application/json",
            success: function (book) {
                book= book.book;
                $(bookName).val(book.name);
                $(bookPrice).val(book.price);
                $(bookGenre).val(book.genre._id);
                $(bookAuthor).val(book.author);
                $(bookPublication).val(book.publication);
                $(bookCondition).val(book.condition);
                $(bookQuantity).val(book.quantity);
                $(coverView).attr('src', './image/'+book.cover);
                $(coverView).attr('style', 'display: block; max-height: 400px;');
            }
        });
        
        const cover = document.getElementById('cover');
        const coverView = document.getElementById('coverView');

        cover.addEventListener('change', function () {
            const file = this.files[0];

            if (file) {
                const reader = new FileReader();

                reader.onload = function (e) {
                    coverView.src = e.target.result;
                    coverView.style.display = 'block';
                };

                reader.readAsDataURL(file);
            } else {
                coverView.src = '#';
                coverView.style.display = 'none';
            }
        });
        $(btnEditBook).click(function (e) { 
            e.preventDefault();
            const data={
                bookId,
                name : $(bookName).val(),
                price : $(bookPrice).val(),
                genre : $(bookGenre).val(),
                author : $(bookAuthor).val(),
                publication : $(bookPublication).val(),
                condition : $(bookCondition).val(),
                quantity : $(bookQuantity).val()
            }
            let error=0;
            $.map(data, function (value, field) {
                if(value =='') error++;
            });
            if(error==0){
                $(err).addClass('d-none');
                    $.ajax({
                    type: "POST",
                    url: `https://bookhavenapi.sankarsan.xyz/api/v1/book/edit`,
                    headers: {
                        Authorization: 'Bearer '+getCookie('token')
                    },
                    data: JSON.stringify(data),
                    contentType: "application/json",
                    success: function (response) {
                        showToast(response.message);
                        // $(addItem)[0].reset();
                        // $(coverView).attr('src', '#');
                        // $(coverView).attr('style', 'display: none; max-height: 400px;');
                    }
                });
            }else{
                $(err).removeClass('d-none');
            }
        });
                hideSpinner();
    });
</script>
<?php require_once 'script.php'; ?>