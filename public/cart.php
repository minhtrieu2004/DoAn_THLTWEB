<?php
include '../includes/header.php';
?>

<section class="py-5">
    <div class="container px-4 px-lg-5 mt-5">
        <div class="row">
            <div class="col-lg-8 mb-4">
                <div class="table-reponsive">
                    <table class="table table-bordered align-middle text-center mb-0">
                        <thead class="text-white" style="background-color: #676f64ff;">
                            <tr>
                                <th scope="col" class="text-start" style="width: 30%;">Sản Phẩm</th>
                                <th scope="col" style="width: 15%;">Giá</th>
                                <th scope="col" style="width: 20%;">Số Lượng</th>
                                <th scope="col" style="width: 15%;">Tạm Tính</th>

                            </tr>
                        </thead>
                        <tbody id="cart-items">
                            <!-- Cart items loaded by JS loadCart() -->
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
                                0 VND
                            </div>
                        </li>

                        <li class="list-group-item d-flex p-0">
                            <div class="p-3 fw-bold text-white" style="background-color: #676f64ff; width: 50%;">Tổng</div>
                            <div id="cart-total" class="p-3 text-end fw-bold border-bottom border-info border-2" style="width: 50%;">
                                0 VND
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

<script>
    // Ensure cart loads when page opens
    document.addEventListener('DOMContentLoaded', function() {
        loadCart();
    });
</script>

<?php
include '../includes/footer.php';
?>