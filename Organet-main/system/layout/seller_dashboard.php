<?php
$TITLE = "MealMate";
include_once '../header/header.php';
@$user_type = $_SESSION['user_type'];
?>

<body class="h-100 bg-light">
<?php
include_once '../component/navbar.php';
include_once '../component/dashboard/dashboard-page.php';
include_once '../component/dashboard/product_create_page.php';
include_once '../component/dashboard/product_list_view.php';
include_once '../component/dashboard/product_details_view.php';

include_once '../component/dashboard/order_details_page.php';

include_once '../component/dashboard/customer_list_view.php';
include_once '../component/dashboard/customer_details.php';

include_once '../component/dashboard/d_man_list_view.php';
include_once '../component/dashboard/d_man_details.php';

?>

<div class="row mb-5 h-auto">

    <div class="col-lg-2 col-md-4 col-sm-12 ">

        <div class="rounded rounded-3 ms-2 mt-3 px-4 position-static py-2  border border-secondary-subtle bg-white" style="background-color: rgba(105,105,107,0.68); min-height: 500px;">
            <div class="border-bottom border-dark text-center pt-3">
                <h5 class="text-dark fw-bold">ADMIN PANEL</h5>
            </div>

            <ul class="sidebar-nav list-unstyled pt-4 ps-3 pe-4">
                <li>
                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center" onclick="Dashboard_Component()" type="button">
                        <i class="nav-icon bi bi-houses text-dark fs-5 me-2"></i>Dashboard
                    </a>
                </li>

                <!-- Product Control Button -->
                <li class="nav-item mt-3">
                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#buttonsGroup" aria-expanded="true">
                        <i class="nav-icon bi bi-columns-gap text-dark me-2 fs-5"></i>Product Control
                    </a>
                    <ul class="nav-group-items collapse mt-2" id="buttonsGroup">
                        <li class="nav-item">
                            <a class="nav-link text-dark" onclick="Products_Component()" type="button">
                                <span class="nav-icon"><i class="bi bi-caret-right"></i></span>Add Product
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" onclick="Products_List()" type="button">
                                <span class="nav-icon"><i class="bi bi-caret-right"></i></span>Product List
                            </a>
                        </li>

                    </ul>
                </li>

                <!-- Delivery Management -->
<!--                <li class="nav-item mt-3">-->
<!--                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center nav-group-toggle" href="#" data-bs-toggle="collapse" data-bs-target="#deliveryManBtn" aria-expanded="true">-->
<!--                        <i class="nav-icon bi bi-truck text-dark me-2 fs-5"></i>Delivery Manage-->
<!--                    </a>-->
<!--                    <ul class="nav-group-items collapse mt-2" id="deliveryManBtn">-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link text-dark" href="#">-->
<!--                                <span class="nav-icon"><i class="bi bi-arrow-right-short"></i></span>Add Delivery Man-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link text-dark" onclick="DMan_List()" type="button">-->
<!--                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>Delivery Man List-->
<!--                            </a>-->
<!--                        </li>-->
<!--                        <li class="nav-item">-->
<!--                            <a class="nav-link text-dark" onclick="DeliveryManDetails()" type="button">-->
<!--                                <span class="nav-icon"><span class="nav-icon-bullet"></span></span>Delivery Man Profile-->
<!--                            </a>-->
<!--                        </li>-->
<!--                    </ul>-->
<!--                </li>-->

                <li class="mb-2 mt-3">
                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center" onclick="DMan_List()" type="button" href="#">
                        <i class="bi bi-truck text-dark me-2 fs-5 text-dark"></i>Delivery Mans
                    </a>
                </li>

                <!-- Customer Section -->
                <li class="mb-2 mt-3">
                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center" onclick="Customer_List()" type="button" href="#">
                        <i class="bi bi-person-video2 text-dark fs-5 me-2"></i>Customers
                    </a>
                </li>
<!--                <li class="mb-2 mt-2">-->
<!--                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center" onclick="CustomerDetails()" type="button" href="#">-->
<!--                        <i class="bi bi-person-fill-gear text-dark fs-5 me-2"></i>Customer Details-->
<!--                    </a>-->
<!--                </li>-->
<!--                <li class="mb-2 mt-2">-->
<!--                    <a class="text-decoration-none text-dark fw-bold fs-6 d-flex align-items-center" onclick="OrderDetails()" type="button" href="#">-->
<!--                        <i class="nav-icon bi bi-houses text-dark fs-5 me-2"></i>Order Details-->
<!--                    </a>-->
<!--                </li>-->
            </ul>
        </div>



    </div>

    <div class="col-lg-10 col-md-8 col-sm-12 mt-4">

        <div class="container-fluid" id="component">


        </div>

    </div>

</div>
<script>
  Dashboard_Component();
</script>




<?php
include_once ('../component/footer.php');
?>

</body>