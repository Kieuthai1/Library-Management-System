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
                    <a href="#">Xem sách</a>
                </li>
            </ol>

            <div class="card mb-3">
                <div class="card-header">
                    <i class="fa fa-info-circle"></i>
                    Xem chi tiết sách
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Thứ tự</th>
                                    <th>Tên</th>
                                    <th>Image</th>
                                    <th>Thể loại</th>
                                    <th>Tác giả</th>
                                    <th>Trạng thái</th>
                                    <th>Yêu cầu mượn</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if(isset($_GET['ids'])) {
                                    $id = $_GET['ids'];
                                    $delete_query = mysqli_query($conn, "DELETE FROM tbl_book WHERE id='$id'");
                                }
                                $select_query = mysqli_query($conn, "SELECT * FROM tbl_book WHERE availability=1");
                                $sn = 1;
                                while($row = mysqli_fetch_array($select_query)) { 
                                ?>
                                    <tr>
                                        <td><?php echo $sn; ?></td>
                                        <td><?php echo $row['book_name']; ?></td>
                                        <td class="center" width="300">
                                            <?php if (!empty($row['bookImage'])) { ?>
                                                <!-- Link to open the image in lightbox -->
                                                <a href="admin/bookimg/<?php echo $row['bookImage']; ?>" data-lightbox="book-<?php echo $row['id']; ?>" data-title="<?php echo $row['book_name']; ?>">
                                                    <img src="admin/bookimg/<?php echo $row['bookImage']; ?>" width="100">
                                                </a>
                                            <?php } else { ?>
                                                <img src="admin/bookimg/default.jpg" width="100"> <!-- ảnh mặc định nếu không có ảnh -->
                                            <?php } ?>
                                        </td>
                                        <td><?php echo $row['category']; ?></td>
                                        <td><?php echo $row['author']; ?></td>
                                        <?php if($row['availability'] == 1) { ?>
                                            <td><span class="badge badge-success">Có sẵn</span></td>
                                        <?php } else { ?>
                                            <td><span class="badge badge-danger">Không có sẵn</span></td>
                                        <?php } 
                                        $id = $row['id'];
                                        $fetch_issue_details = mysqli_query($conn, "SELECT status FROM tbl_issue WHERE user_id='$ids' AND book_id='$id'");
                                        $res = mysqli_fetch_row($fetch_issue_details);
                                        if(!empty($res)){
                                            $res = $res[0];
                                        }
                                        if($res == 1) {
                                            ?>
                                            <td><span class="badge badge-success">Đã mượn</span></td>
                                        <?php } else if($res == 2) { ?>
                                            <td><span class="badge badge-danger">Từ chối</span></td>
                                        <?php } else if($res == 3) { ?>
                                            <td><span class="badge badge-primary">Đã gửi yêu cầu</span></td>
                                        <?php } else { ?>
                                            <td><a href="book-issue.php?id=<?php echo $row['id']; ?>"><button class="btn btn-success">Mượn</button></a></td>
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

<script language="JavaScript" type="text/javascript">
function confirmDelete(){
    return confirm('Are you sure want to delete this Book?');
}
</script>
