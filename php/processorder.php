<?php
include("database.php");
include("headers.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $req = $_POST['request'];
    if ($req == 'getorderno') {
        // =====================select Orders.Finished, order_list.OrderID and the result will be group by order_list.OrderID===
        $result = $conn->query("SELECT Orders.Finished, order_list.OrderID FROM Orders JOIN order_list ON Orders.ID = order_list.OrderID GROUP BY order_list.OrderID");
        // =====================================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'processfinishorder') {
        $orderId = $_POST['orderno'];
        // ===================get Orders by order id==============================================================
        $result = $conn->query("SELECT * FROM Orders WHERE ID='$orderId'");
        // =======================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'processorder') {
        $orderno = $_POST['orderno'];
        // =======================================================================================================
        $result = $conn->query("SELECT DATE_FORMAT(Orders.Date, '%Y %m %d') as Date, Orders.Price as Order_Price, order_list.ProductID, Products.Name, Products.Description, Products.ImageURL, Products.Price FROM Orders, order_list, Products WHERE order_list.OrderID='$orderno' AND Orders.ID=order_list.OrderID AND order_list.ProductID=Products.ID GROUP by order_list.ProductID");
        // =======================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'updateorder') {
        $orderId = $_POST['orderno'];
        // ===========update Orders's Finished to True by order id================================================
        $conn->query("UPDATE Orders Set Finished=1 WHERE ID='$orderId'");
        // =======================================================================================================
    }
    if ($req == 'updateprofit') {
        $userId = $_POST['userno'];
        $profit = $_POST['profit'];
        // ===========update Seller's Profit by user id ==========================================================
        $conn->query("UPDATE Seller Set Profit=(SELECT Profit FROM Seller WHERE ID='$userId')+'$profit' WHERE ID='$userId'");
        // =======================================================================================================
    }
    if ($req == 'getsalesamount') {
        $orderId = $_POST['orderno'];
        // =====================get order_list's ProductID and Amount by orderId, and result will be group by ProductID=
        $result = $conn->query("SELECT ProductID, Amount FROM order_list WHERE orderID='$orderId' GROUP BY ProductID");
        // =============================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'updatesalesamount') {
        $productID = $_POST['productID'];
        $amount = $_POST['amount'];
        // ===========update Products's Sales_volume and Inventory by product id==================================
        $conn->query("UPDATE Products SET Sales_volume=(SELECT Sales_volumn FROM Products WHERE products.ID='$productID')+'$amount', Inventory=(SELECT Inventory FROM Products WHERE products.ID='$productID')-'$amount' WHERE products.ID='$productID'");
        //有問題
        // =======================================================================================================
        
        // ===========update Products's Available to Fasle, if Inventory == 0 ===================================
        $conn->query("UPDATE Products SET Available=0 WHERE Inventory=0");
        //有問題
        // =======================================================================================================
    }
}
