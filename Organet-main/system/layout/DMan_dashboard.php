<?php
$TITLE = "MealMate";
include_once '../header/header.php';
@$user_type = $_SESSION['user_type'];
?>

<body class="bg-light">
<?php
include_once '../component/navbar.php';
include_once '../component/dashboard/d_man_dashboard-page.php';
include_once '../component/dashboard/order_details_page.php';
include_once '../component/dashboard/d_man_profile_view.php';

?>

<div class="row mb-5">

    <div class="col-lg-2 col-md-4 col-sm-12 border-end ">

        <div class="rounded rounded-4 ms-2 mt-3 px-4 position-static"
             style="background-color: rgba(50, 74, 81, 0.75)">

            <div class="border-bottom d-block text-center pt-2">
                <p class="text-black-50 fw-bolder fs-6 ">My Panel</p>
            </div>

            <ul class="sidebar-nav list-unstyled pt-4 ps-3 pe-5">

                <li class="">
                    <a class="text-decoration-none text-white fw-bold fs-6 d-flex align-items-center" onclick="DMan_Dashboard_Component()" type="button">
                        <i class="nav-icon bi bi-houses text-white fs-4 me-2 "></i>Dashboard
                    </a>
                </li>

           <!--  product control button-->
                <li class="mb-2 mt-2">
                    <a class="text-decoration-none text-white fw-bold fs-6 d-flex align-items-center" onclick="DeliveryManProfile()" type="button" href="#">
                        <i class="bi bi-person-fill-gear text-white fs-4 me-2"></i>My Profile
                    </a>
                </li>

                <li class="mb-2 mt-2">
                    <a class="text-decoration-none text-white fw-bold fs-6 d-flex align-items-center" onclick="OrderDetails()" type="button" href="#">
                        <i class="nav-icon bi bi-houses text-white fs-4 me-2 "></i>Order Details
                    </a>
                </li>

            </ul>

        </div>

    </div>

    <div class="col-lg-10 col-md-8 col-sm-12 mt-4">

        <div class="container-fluid" id="component">


        </div>

    </div>

</div>

<?php
include_once ('../component/footer.php');
?>


<script>
    DMan_Dashboard_Component();
</script>



</body>