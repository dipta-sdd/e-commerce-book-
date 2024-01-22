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
                <style>
      /*======================
    404 page
=======================*/

      .page_404 {
        padding: 40px 0;
        background: #fff;
        font-family: "Arvo", serif;
      }

      .page_404 img {
        width: 100%;
      }

      .four_zero_four_bg {
        background-image: url(https://cdn.dribbble.com/users/285475/screenshots/2083086/dribbble_1.gif);
        height: 400px;
        background-position: center;
      }

      .four_zero_four_bg h1 {
        font-size: 80px;
      }

      .four_zero_four_bg h3 {
        font-size: 80px;
      }

      .link_404 {
        color: #fff !important;
        padding: 10px 20px;
        background: #39ac31;
        margin: 20px 0;
        display: inline-block;
      }
      .contant_box_404 {
        margin-top: -50px;
      }
    </style>
            <section class="page_404">
                <div class="container">
                    <div class="row">
                    <div class="col-sm-12">
                        <div class="col-sm-12 text-center">
                        <div class="four_zero_four_bg">
                            <h1 class="text-center">404</h1>
                        </div>

                        <div class="contant_box_404">
                            <h3 class="h2">Look like you're lost</h3>

                            <p>the page you are looking for not avaible!</p>

                            <a href="/" class="link_404">Go to Home</a>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </section>
        </div>
        <?php require_once 'footer.php'; ?>
    </div>

<?php require_once 'body.php'; ?>
<script>
    $(document).ready(function () {
        sidebarActivate('./tmp');
        let userRole = $('#userType').attr('usertype');
        setTimeout(hideSpinner, 500);
    });
</script>
<?php require_once 'script.php'; ?>