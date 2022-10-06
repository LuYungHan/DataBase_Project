<?php
include("database.php");
include("headers.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $req = $_POST['request'];
    if ($req == 'checkOrderRecord') {
        $userno = $_POST['userno'];
        // ====================get all orders by buy buyer id=====================================================
        //$orders = $conn->query("SELECT Orders.ID, DATE_FORMAT(Orders.Date, '%Y %m %d') as Date, Orders.Price, Orders.CouponID, Orders.Finished FROM users JOIN Orders ON users.ID=Orders.buyerID WHERE users.ID='$userno'");
        $orders = $conn->query("SELECT Orders.ID, DATE_FORMAT(Orders.Date, '%Y %m %d') as Date, Orders.Price, Orders.CouponID, Orders.Finished FROM Orders WHERE ID='$userno'");
        // =======================================================================================================
        if ($orders->num_rows > 0) {
            while (($row_result = $orders->fetch_assoc()) !== null) {
                if ($row_result['CouponID'] != NULL) {
                    $couponId = $row_result['CouponID'];
                    // =========get all coupon's amount by coupon id==========================================================
                    $amount = $conn->query("SELECT amount FROM coupon WHERE ID='$couponId'");
                    // =======================================================================================================
                    $amount = $amount->fetch_assoc()['Amount'];
                } else {
                    $amount = 0;
                }
                $row_result["Coupon_Amount"] = $amount;
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'getOrderlist') {
        $orderId = $_POST['orderno'];
        // =======================================================================================================
        $result = $conn->query("SELECT Products.Id, `Name`, Price, `Description`, ImageURL, order_list.Amount FROM order_list, Products WHERE order_list.OrderID='$orderId' AND order_list.ProductID=Products.id");
        //$result = $conn->query("SELECT DATE_FORMAT(Orders.Date, '%Y %m %d') as Date, Orders.Price as Order_Price, order_list.ProductID, Products.Name, Products.Description, Products.ImageURL, Products.Price FROM Orders, order_list, Products WHERE order_list.OrderID='$orderno' AND Orders.ID=order_list.OrderID AND order_list.ProductID=Products.ID GROUP by order_list.ProductID");
        // =======================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
}
