<?php
// Bắt đầu Session
session_start();

// -------------------------------------------------------------
// BẢO MẬT: Kiểm tra quyền Admin trước khi cho phép INSERT
// -------------------------------------------------------------
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] !== 'admin') {
    die("Lỗi: Bạn không có quyền thực hiện thao tác này.");
}

// -------------------------------------------------------------
// CHỈ XỬ LÝ NẾU ĐÂY LÀ YÊU CẦU POST HỢP LỆ
// -------------------------------------------------------------
if ($_SERVER["REQUEST_METHOD"] == "POST" && $_POST['action'] === 'create') {

    // Nhúng kết nối database
    include '../config/db.php'; // Đảm bảo $pdo đã được thiết lập

    // 1. Lấy và làm sạch dữ liệu TỪ FORM
    $name        = trim($_POST['productName'] ?? '');
    $price       = (int) ($_POST['productPrice'] ?? 0);
    $stock       = (int) ($_POST['productStock'] ?? 0);
    $description = trim($_POST['productDesc'] ?? '');
    $category_id = (int) ($_POST['categoryId'] ?? 0); // LẤY ID DANH MỤC

    // Kiểm tra dữ liệu bắt buộc (Thêm category_id vào kiểm tra)
    if (empty($name) || $price <= 0 || $stock < 0 || $category_id <= 0) {
        $_SESSION['error'] = "Vui lòng nhập đầy đủ Tên, Giá, Tồn kho và chọn Danh mục hợp lệ.";
        header("Location: ../admin/admin_products.php");
        exit;
    }

    // Khởi tạo biến lưu đường dẫn ảnh
    $image_main = null;

    // 2. XỬ LÝ FILE UPLOAD
    if (isset($_FILES['productImage']) && $_FILES['productImage']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['productImage']['tmp_name'];
        $fileName = $_FILES['productImage']['name'];

        // Tạo tên file mới duy nhất và lấy đuôi file
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;

        // Thư mục lưu trữ ảnh (ĐƯỜNG DẪN CẦN PHẢI CHÍNH XÁC)
        // Đảm bảo thư mục này đã được tạo và có quyền ghi.
        $uploadFileDir = '../assets/images/';
        $dest_path = $uploadFileDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            // LƯU ĐƯỜNG DẪN TƯƠNG ĐỐI VÀO DB
            $image_main = 'assets/images/' . $newFileName;
        } else {
            $_SESSION['error'] = "Lỗi khi di chuyển file đã upload. Kiểm tra quyền ghi thư mục.";
            header("Location: ../admin/admin_products.php");
            exit;
        }
    } else {
        $_SESSION['error'] = "Vui lòng chọn ảnh cho sản phẩm.";
        header("Location: ../admin/admin_products.php");
        exit;
    }

    // 3. Chuẩn bị truy vấn INSERT CUỐI CÙNG
    try {
        // Cập nhật câu lệnh SQL để THÊM category_id và image_main
        $sql = "INSERT INTO products (name, price, stock, description, category_id, image_main) 
                VALUES (:name, :price, :stock, :description, :category_id, :image_main)";

        $stmt = $pdo->prepare($sql);

        // Gán tham số (Bind Parameters)
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':stock', $stock);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':category_id', $category_id); // GÁN ID DANH MỤC
        $stmt->bindParam(':image_main', $image_main);   // GÁN ĐƯỜNG DẪN ẢNH

        // 4. Thực thi
        if ($stmt->execute()) {
            $_SESSION['success'] = "Sản phẩm **" . htmlspecialchars($name) . "** đã được thêm thành công!";
        } else {
            $_SESSION['error'] = "Lỗi khi thêm sản phẩm vào database. Vui lòng kiểm tra log SQL.";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Lỗi Database: " . $e->getMessage();
    }
} else {
    // Nếu không phải POST hoặc action không đúng, chuyển hướng về trang products.php (không phải admin_products.php)
    $_SESSION['error'] = "Yêu cầu không hợp lệ.";
}

// Chuyển hướng người dùng quay lại trang quản lý sản phẩm
header("Location: ../admin/admin_products.php");
exit;
