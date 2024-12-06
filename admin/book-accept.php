<?php 
include ('../connection.php');

// Xử lý khi xác nhận yêu cầu mượn sách (id)
if(!empty($_GET['id'])) {
    $id = $_GET['id'];
    $duedate = date('Y-m-d');
    // Tính ngày hết hạn là một tháng kể từ ngày hôm nay
    $newdate = date('Y-m-d', strtotime($duedate . ' + 1 months'));

    // Cập nhật trạng thái yêu cầu mượn sách và ngày mượn, ngày hết hạn
    $update_issue = mysqli_query($conn, "UPDATE tbl_issue SET status=1, issue_date=CURDATE(), due_date='$newdate' WHERE id='$id'");

    // Lấy thông tin ID sách từ yêu cầu mượn
    $select_book_id = mysqli_query($conn, "SELECT book_id FROM tbl_issue WHERE id='$id'");
    $book_id = mysqli_fetch_row($select_book_id);
    $book_id = $book_id[0];

    // Lấy số lượng sách hiện có trong kho
    $select_quantity = mysqli_query($conn, "SELECT quantity FROM tbl_book WHERE id='$book_id'");
    $number = mysqli_fetch_row($select_quantity);
    $count = $number[0];

    // Giảm số lượng sách đi 1
    $count = intval($count) - 1;

    // Cập nhật lại số lượng sách trong kho
    $update_book = mysqli_query($conn, "UPDATE tbl_book SET quantity='$count' WHERE id='$book_id'");

    // Kiểm tra kết quả cập nhật và thông báo cho người dùng
    if($update_issue && $update_book) {
        ?>
        <script type="text/javascript">
            alert("Sách đã mượn.");
            window.location.href="issue-request.php";
        </script>
        <?php
    }
}

// Xử lý khi từ chối yêu cầu mượn sách (ids)
if(!empty($_GET['ids'])) {
    $ids = $_GET['ids'];

    // Cập nhật trạng thái yêu cầu mượn sách thành "bị từ chối"
    $update_issue = mysqli_query($conn, "UPDATE tbl_issue SET status=2 WHERE id='$ids'");

    // Kiểm tra kết quả cập nhật và thông báo cho người dùng
    if($update_issue) {
        ?>
        <script type="text/javascript">
            alert("Rejected.");
            window.location.href="issue-request.php";
        </script>
        <?php
    }
}
?>
