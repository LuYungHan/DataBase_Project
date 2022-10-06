<?php
include("database.php");
include("headers.php");
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $req = $_POST['request'];
    if ($req == 'getmyproduct') {
        // =====get all products's Name、Price、Description、Sales_volume、Inventory、ImageURL、Available、ID=====
        $result = $conn->query("SELECT * FROM products");
        // =======================================================================================================
        if ($result->num_rows > 0) {
            while (($row_result = $result->fetch_assoc()) !== null) {
                $row[] = $row_result;
            }
            echo json_encode(array("data" => $row));
            $conn->close();
        }
    }
    if ($req == 'offproduct') {
        //echo("<script>console.log('PHP: offproduct');</script>");
        $productID = $_POST['productID'];
        // ==========close product by product id (hint: update available to True or False) =======================
        $conn->query("UPDATE products SET Available=0");
        //$conn->query("UPDATE products SET Available=0 WHERE ID='$productID'");
        // =======================================================================================================
    }
    if ($req == 'launchproduct') {
        $productID = $_POST['productID'];
        // ===========open product by product id (hint: update available to True or False)=======================
        $conn->query("UPDATE products SET Available=1");
        //$conn->query("UPDATE products SET Available=1 WHERE ID='$productID'");
        // =======================================================================================================
    }
    if ($req == 'replenishment') {
        $productID = $_POST['productID'];
        $inventory = $_POST['inventory'];
        // =====================update product's inventory by product id=========================================
        //$success = $conn->query("UPDATE products SET Inventory=(SELECT Inventory FROM products WHERE ID='$productID')+'$inventory' WHERE ID='$productID'");
        $success = $conn->query("UPDATE products SET Inventory='$inventory'");
        // =======================================================================================================
        if ($success) {
            echo json_encode(array("success" => true));
        } else {
            echo json_encode(array("success" => false));
        }

    }
}
