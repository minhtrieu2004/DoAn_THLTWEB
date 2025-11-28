<?php
session_start();
?>

<?php
include '../includes/header.php';
?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="table-reponsive">
                    <table class="table table-borderd align-midle text-center mb-0">
                        <thead class="text-white" style="background-color: #3ead1cff;">
                            <tr>
                                <th scope="col" class="text-start" style="width: 40%;">Sản Phẩm</th>
                                <th scope="col" style="width: 20%;">Giá</th>
                                <th scope="col" style="width: 20%;">Số Lượng</th>
                                <th scope="col" style="width: 20%;">Tạm Tính</th>
                            </tr>
                        </thead>
                    </table>

                </div>
            </div>
            <div class="col-lg-4">

            </div>
        </div>
    </div>

</section>


<?php
include '../includes/footer.php';
?>