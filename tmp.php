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