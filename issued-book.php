<?php
session_start();
include ('connection.php');
$name = $_SESSION['user_name'];
$ids = $_SESSION['id'];
if(empty($ids)) {
    header("Location: index.php"); 
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
            <a href="#">Xem yêu cầu mượn sách</a>
          </li>
        </ol>

        <div class="card mb-3">
          <div class="card-header">
            <i class="fa fa-info-circle"></i> Yêu cầu mượn</div>
            <div class="card-body">
              <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                  <thead>
                    <tr>
                      <th>S.No.</th>
                      <th>Tên sách</th>
                      <th>Ảnh sách</th>
                      <th>Thể loại sách</th>
                      <th>Ngày mượn</th>
                      <th>Hết hạn</th>
                     
                 
                      <th>Thao tác</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    // Truy vấn kết hợp bảng tbl_issue và tbl_book để lấy thông tin sách, ảnh
                    $select_query = mysqli_query($conn, "SELECT tbl_issue.book_id, tbl_book.book_name, tbl_book.category, tbl_issue.issue_date, tbl_issue.due_date, tbl_book.bookImage
                                                          FROM tbl_issue
                                                          INNER JOIN tbl_book ON tbl_issue.book_id = tbl_book.id
                                                          WHERE tbl_issue.user_id = '$ids' AND tbl_issue.status = 1");
                    $sn = 1;
                    while($row = mysqli_fetch_array($select_query)) { 
                    ?>
                      <tr>
                        <td><?php echo $sn; ?></td>
                        <td><?php echo $row['book_name']; ?></td>
                         <!-- Hiển thị ảnh sách nếu có -->
                         <td>
                          <?php if (!empty($row['bookImage'])) { ?>
                            <a href="admin/bookimg/<?php echo $row['bookImage']; ?>" data-lightbox="book-<?php echo $row['book_id']; ?>" data-title="<?php echo $row['book_name']; ?>">
                              <img src="admin/bookimg/<?php echo $row['bookImage']; ?>" width="100">
                            </a>
                          <?php } else { ?>
                            <img src="admin/bookimg/default.jpg" width="100"> <!-- ảnh mặc định nếu không có ảnh -->
                          <?php } ?>
                        </td>
                        <td><?php echo $row['category']; ?></td>
                        <td><?php echo $row['issue_date']; ?></td>
                        <td><?php echo $row['due_date']; ?></td>
                        
                       

                        <!-- Thêm phần trả sách -->
                        <td>
                          <a href="book-return.php?id=<?php echo $row['book_id']; ?>"><button class="btn btn-success">Trả sách</button></a>
                        </td>
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
  </div>

<a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
</a>

<?php include('include/footer.php'); ?>
