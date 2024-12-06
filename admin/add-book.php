<?php
session_start();
include('../connection.php');

$name = $_SESSION['name'];
$id = $_SESSION['id'];

if (empty($id)) {
    header("Location: index.php");
}

if (isset($_REQUEST['sbt-book-btn'])) {
    $book_name = $_POST['book_name'];
    $category_name = $_POST['category_name'];
    $isbn = $_POST['isbn'];
    $author_name = $_POST['author_name'];
    $publisher_name = $_POST['publisher_name'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $location_name = $_POST['location_name'];
    $availability = $_POST['availability'];

    // Kiểm tra nếu có ảnh được upload
    if (isset($_FILES["bookpic"]) && $_FILES["bookpic"]["error"] == 0) {
        $bookimg = $_FILES["bookpic"]["name"];
        // Lấy phần mở rộng của file
        $extension = substr($bookimg, strlen($bookimg) - 4, strlen($bookimg));
        // Định dạng file cho phép
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");

        if (in_array($extension, $allowed_extensions)) {
            // Tạo tên mới cho ảnh
            $imgnewname = md5($bookimg . time()) . $extension;
            // Di chuyển file vào thư mục
            move_uploaded_file($_FILES["bookpic"]["tmp_name"], "bookimg/" . $imgnewname);
        } else {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
            exit;
        }
    } else {
        // Nếu không có ảnh, gán ảnh mặc định
        $imgnewname = 'default_image.jpg';
    }

    // Sử dụng Prepared Statement để tránh SQL Injection
    $stmt = $conn->prepare("INSERT INTO tbl_book (book_name, bookImage, category, isbnno, author, publisher, price, quantity, place, availability) 
                            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssdsdsi", $book_name, $imgnewname, $category_name, $isbn, $author_name, $publisher_name, $price, $quantity, $location_name, $availability);

    if ($stmt->execute()) {
        echo "<script>alert('Book added successfully.'); window.location.href='add-book.php';</script>";
    } else {
        echo "<script>alert('Error adding book.');</script>";
    }

    $stmt->close();
}
?>

<?php include('include/header.php'); ?>
<div id="wrapper">
    <?php include('include/side-bar.php'); ?>

    <div id="content-wrapper">
        <div class="container-fluid">

            <!-- Breadcrumbs-->
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="#">Thêm sách</a>
                </li>
            </ol>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i>
                    Đăng kí sách
                </div>

                <form method="post" class="form-valide" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="item">Tên sách <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="book_name" id="book_name" class="form-control" placeholder="Tên sách" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="item">Hình ảnh sách <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input class="form-control" type="file" name="bookpic" autocomplete="off" required="required" />
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="leave-type">
                                Thể loại<span class="text-danger">*</span>
                            </label>
                            <div class="col-lg-6">
                                <select class="form-control" id="category_name" name="category_name" required>
                                    <option value="">Chọn thể loại</option>
                                    <?php 
                                    $fetch_category = mysqli_query($conn, "SELECT * FROM tbl_category WHERE status=1");
                                    while($row = mysqli_fetch_array($fetch_category)){
                                    ?>
                                    <option><?php echo $row['category_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Mã số tiêu chuẩn quốc tế cho sách(ISBN) <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="isbn" id="isbn" class="form-control" placeholder="ISBN" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Tác giả <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="author_name" id="author_name" class="form-control" placeholder="Tác giả" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Nhà xuất bản <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="publisher_name" id="publisher_name" class="form-control" placeholder="Nhà xuất bản" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Giá <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="price" id="price" class="form-control" placeholder="Giá" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Số lượng <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Số lượng" required>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="leave-type">Địa chỉ <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" id="location_name" name="location_name" required>
                                    <option value="">Chọn địa chỉ</option>
                                    <?php 
                                    $fetch_location = mysqli_query($conn, "SELECT * FROM tbl_location WHERE status=1");
                                    while($row = mysqli_fetch_array($fetch_location)){
                                    ?>
                                    <option><?php echo $row['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="status">Trạng thái sách <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" id="availability" name="availability" required>
                                    <option value="">Chọn trạng thái</option>
                                    <option value="1">Có sẵn</option>
                                    <option value="0">Không có sẵn</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" name="sbt-book-btn" class="btn btn-primary">Đăng kí</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

<?php include('include/footer.php'); ?>
