<?php
include '../includes/header.php';
?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="table-reponsive">
                    <table class="table table-borderd align-midle text-center mb-0">
                        <thead class="text-white" style="background-color: #676f64ff;">
                            <tr>
                                <th scope="col" class="text-start" style="width: 40%;">Sản Phẩm</th>
                                <th scope="col" style="width: 20%;">Giá</th>
                                <th scope="col" style="width: 20%;">Số Lượng</th>
                                <th scope="col" style="width: 20%;">Tạm Tính</th>
                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <?php
                                //code o day
                                // Dữ liệu được load từ script.js 
                            ?>
                        </tbody>
                    </table>

                </div>
            </div>
            <div class="col-lg-4">
                <div class="card p-0 border-0">
                    <div class="card-header text-white fw-bold py-3" style="background-color: #676f64ff; font-size: 1.25rem;">
                        CỘNG GIỎ HÀNG
                    </div>
                    <ul class="list-group list-group-flush">

                        <li class="list-group-item d-flex p-0">
                            <div class="p-3 fw-bold text-white" style="background-color: #676f64ff; width: 50%;">Tạm tính</div>
                            <div id="cart-subtotal" class="p-3 text-end fw-bold border-bottom border-info border-2" style="width: 50%;">
                                <?php // echo number_format($total_subtotal, 0, ',', '.'); 
                                ?> đ
                            </div>
                        </li>

                        <li class="list-group-item d-flex p-0">
                            <div class="p-3 fw-bold text-white" style="background-color: #676f64ff; width: 50%;">Tổng</div>
                            <div id="cart-total" class="p-3 text-end fw-bold border-bottom border-info border-2" style="width: 50%;">
                                <?php // echo number_format($final_total, 0, ',', '.'); 
                                ?> đ
                            </div>
                        </li>
                    </ul>
                    <div class="p-0 mt-3">
                        <button class="btn text-white w-100 py-3 fw-bold" style="background-color: #9e5c44ff; font-size: 1.15rem;">
                            TIẾN HÀNH THANH TOÁN
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

</section>


<?php
include '../includes/footer.php';
?>