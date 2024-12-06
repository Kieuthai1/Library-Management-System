<?php 
session_start();
include('../connection.php');

$name = $_SESSION['name'];
$id = $_SESSION['id'];
if(empty($id)) {
    header("Location: index.php"); 
    exit;
}

include('include/header.php'); 
?>
  <div id="wrapper">
    <?php include('include/side-bar.php'); ?>
    <div id="content-wrapper">
      <div class="container-fluid">
        <!-- Breadcrumbs-->
        <ol class="breadcrumb">
          <li class="breadcrumb-item">
            <a href="#">Xem yêu cầu mượn sách</a>
          </li>
        </ol>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-info-circle"></i> Yêu cầu mượn
          </div>
          <div class="card-body">
            <div class="table-responsive">
              <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                <thead>
                  <tr>
                    <th>S.No.</th>
                    <th>Tên sách</th>
                    <th>Ảnh sách</th>
                    <th>Tên người dùng</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                  </tr>
                </thead>
                <tbody>
                  <?php 
                  // Truy vấn kết hợp bảng tbl_issue và tbl_book để lấy thông tin sách và ảnh
                  $select_query = mysqli_query($conn, "SELECT tbl_issue.status, tbl_issue.book_id, tbl_book.book_name, tbl_issue.id, tbl_issue.user_name, tbl_book.quantity, tbl_book.bookImage 
                                                        FROM tbl_issue 
                                                        INNER JOIN tbl_book ON tbl_book.id = tbl_issue.book_id");
                  $sn = 1;
                  while($row = mysqli_fetch_array($select_query)) { 
                  ?>
                    <tr>
                      <td><?php echo $sn; ?></td>
                      <td><?php echo $row['book_name']; ?></td>

                      <!-- Hiển thị ảnh sách -->
                      <td>
                        <?php 
                        // Kiểm tra xem có ảnh không
                        if (!empty($row['bookImage']) && file_exists("bookimg/".$row['bookImage'])) { ?>
                          <a href="bookimg/<?php echo $row['bookImage']; ?>" data-lightbox="book-<?php echo $row['book_id']; ?>" data-title="<?php echo $row['book_name']; ?>">
                            <img src="bookimg/<?php echo $row['bookImage']; ?>" width="100">
                            
                          </a>
                        
                        <?php } else { ?>
                        
                          <img src="/bookimg/default.jpg" width="100"> <!-- ảnh mặc định nếu không có ảnh -->
                        <?php } ?>
                      </td>

                      <td><?php echo $row['user_name']; ?></td>
                      <td><?php echo $row['quantity']; ?></td>

                      <!-- Trạng thái -->
                      <?php
                      if(!empty($row['status']) && $row['status'] == 1) { ?>
                        <td><span class="badge badge-primary">Sách đã mượn</span></td>
                      <?php } else if($row['status'] == 2) { ?>
                        <td><span class="badge badge-danger">Rejected</span>
                            <a href="book-accept.php?id=<?php echo $row['id']; ?>"><button class="btn btn-success">Xác nhận</button></a>
                        </td>
                      <?php } else { ?>
                        <td>
                          <a href="book-accept.php?id=<?php echo $row['id']; ?>"><button class="btn btn-success">Chấp nhận</button></a>
                          <a href="book-accept.php?ids=<?php echo $row['id']; ?>"><button class="btn btn-danger">Từ chối</button></a>
                        </td>
                      <?php } ?>
                    </tr>
                  <?php $sn++; } ?>
                </tbody>
              </table>
            </div>
          </div>                   
        </div>
      </div>
    </div>
  </div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include('include/footer.php'); ?>
