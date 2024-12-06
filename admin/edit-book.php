<?php
session_start();
include ('../connection.php');
$id = $_SESSION['id'];
$name = $_SESSION['name'];
if(empty($id)) {
    header("Location: index.php"); 
}

$id = $_GET['id'];
$fetch_query = mysqli_query($conn, "SELECT * FROM tbl_book WHERE id='$id'");
$row = mysqli_fetch_array($fetch_query);

if(isset($_REQUEST['save-book-btn'])) {
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
        $extension = substr($bookimg, strlen($bookimg) - 4, strlen($bookimg)); // Định dạng file
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        
        if (in_array($extension, $allowed_extensions)) {
            // Tạo tên mới cho ảnh
            $imgnewname = md5($bookimg . time()) . $extension;
            move_uploaded_file($_FILES["bookpic"]["tmp_name"], "bookimg/" . $imgnewname);
        } else {
            echo "<script>alert('Invalid format. Only jpg / jpeg / png / gif format allowed');</script>";
            exit;
        }
    } else {
        // Nếu không thay đổi ảnh, giữ nguyên ảnh cũ
        $imgnewname = $row['bookImage'];
    }

    // Cập nhật thông tin sách, bao gồm cả ảnh mới nếu có
    $update_book = mysqli_query($conn, "UPDATE tbl_book 
                                        SET book_name='$book_name', 
                                            category='$category_name', 
                                            isbnno='$isbn', 
                                            author='$author_name', 
                                            publisher='$publisher_name', 
                                            price='$price', 
                                            quantity='$quantity', 
                                            place='$location_name',  
                                            availability='$availability', 
                                            bookImage='$imgnewname' 
                                        WHERE id='$id'");

    if ($update_book) {
        echo "<script type='text/javascript'>
                alert('Book updated successfully.');
                window.location.href='view-book.php';
              </script>";
    } else {
        echo "<script type='text/javascript'>
                alert('Error updating book.');
              </script>";
    }
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
                    <a href="#">Chỉnh sửa sách</a>
                </li>
            </ol>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i> Sửa chi tiết
                </div>

                <form method="post" class="form-valide" enctype="multipart/form-data">
                    <div class="card-body">
                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="book_name">Tên sách <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="book_name" id="book_name" class="form-control" placeholder="Nhập Tên sách" required value="<?php echo $row['book_name']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="category_name">Thể loại <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" id="category_name" name="category_name" required>
                                    <option value="">Chọn thể loại</option>
                                    <?php 
                                        $fetch_category = mysqli_query($conn, "SELECT * FROM tbl_category WHERE status=1");
                                        while($rows = mysqli_fetch_array($fetch_category)) {
                                    ?>
                                    <option <?php if($rows['category_name'] == $row['category']) echo 'selected="selected"'; ?>><?php echo $rows['category_name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="isbn">Mã số tiêu chuẩn quốc tế cho sách (ISBN) <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="isbn" id="isbn" class="form-control" placeholder="Nhập ISBN" required value="<?php echo $row['isbnno']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="author_name">Tác giả <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="author_name" id="author_name" class="form-control" placeholder="Nhập tác giả" required value="<?php echo $row['author']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="publisher_name">Nhà xuất bản <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="publisher_name" id="publisher_name" class="form-control" placeholder="Nhập tên nhà xuất bản" required value="<?php echo $row['publisher']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="price">Giá <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="price" id="price" class="form-control" placeholder="Nhập giá" required value="<?php echo $row['price']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="quantity">Số lượng <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <input type="text" name="quantity" id="quantity" class="form-control" placeholder="Nhập số lượng" required value="<?php echo $row['quantity']; ?>">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="location_name">Địa điểm <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" id="location_name" name="location_name" required>
                                    <option value="">Chọn địa điểm</option>
                                    <?php 
                                        $fetch_location = mysqli_query($conn, "SELECT * FROM tbl_location WHERE status=1");
                                        while($rows = mysqli_fetch_array($fetch_location)) {
                                    ?>
                                    <option <?php if($rows['name'] == $row['place']) echo 'selected="selected"'; ?>><?php echo $rows['name']; ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="availability">Trạng thái <span class="text-danger">*</span></label>
                            <div class="col-lg-6">
                                <select class="form-control" id="availability" name="availability" required>
                                    <option value="">Chọn trạng thái</option>
                                    <option value="1" <?php if($row['availability'] == 1) echo 'selected="selected"'; ?>>Có sẵn</option>
                                    <option value="0" <?php if($row['availability'] == 0) echo 'selected="selected"'; ?>>Không có sẵn</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-lg-4 col-form-label" for="bookpic">Ảnh sách</label>
                            <div class="col-lg-6">
                                <input class="form-control" type="file" name="bookpic" />
                                <small>Ảnh hiện tại: <img src="bookimg/<?php echo $row['bookImage']; ?>" width="100" /></small>
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-lg-8 ml-auto">
                                <button type="submit" name="save-book-btn" class="btn btn-primary">Lưu</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>            
        </div>
    </div>
</div>
<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>
<?php include('include/footer.php'); ?>
