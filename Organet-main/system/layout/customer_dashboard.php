<?php
$TITLE = "MealMate";
include_once '../header/header.php';
@$user_type = $_SESSION['user_type'];


include_once '../../helper/db.connection.php';
$db = $mm_conn;

$id=$_SESSION['id'];

$customerProfilesQuery = "SELECT cus_name FROM customer_profiles";
$Result = mysqli_query($db, $customerProfilesQuery);

$data = mysqli_fetch_assoc($Result);


?>

<body class="bg-light">
<?php
include_once '../component/navbar.php';
include_once '../component/cusDashboard/cus-dashboard-page.php';
include_once '../component/cusDashboard/my-order-details-page.php';
include_once '../component/cusDashboard/my_profile_view.php';


?>

<div class="container min-vh-100">
    <div class="row mt-5">
        <div class="col-lg-3 col-md-3 col-sm-12 border-end bg-white">
            <div class="container-fluid">

                <div class="align-items-center py-3 border-bottom">
                    <p class="mb-0">
                        <span>Hello,&nbsp;</span><span id="lzd_current_logon_user_name"><?php echo $data['cus_name']?></span>
                    </p>
                </div>

                <div class="pt-2">
                    <div>
                        <p class="text-black-50 fw-bolder fs-6 mb-0">Manage My Account</p>
                        <ul class="list-group mt-0 pt-0">

                            <li class="list-group-item border border-0">
                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value="" id="firstRadio" checked
                                       onclick="Cus_Dashboard_Component()">
                                <label class="form-check-label" for="firstRadio">Over-view</label>
                            </li>
                            <li class="list-group-item border border-0">
                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value="" id="firstRadio"
                                       onclick="MyProfileView()">
                                <label class="form-check-label" for="firstRadio">My Profile</label>
                            </li>
<!--                            <li class="list-group-item border border-0">-->
<!--                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value=""-->
<!--                                       id="secondRadio">-->
<!--                                <label class="form-check-label" for="secondRadio">Address Book</label>-->
<!--                            </li>-->
<!--                            <li class="list-group-item border border-0">-->
<!--                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value=""-->
<!--                                       id="thirdRadio">-->
<!--                                <label class="form-check-label" for="thirdRadio">My Payment Options</label>-->
<!--                            </li>-->
                        </ul>
                    </div>

                    <div>
                        <p class="text-black-50 fw-bolder fs-6 mb-0">My Order</p>
                        <ul class="list-group ">
<!--                            <li class="list-group-item border border-0">-->
<!--                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value="" id="firstRadio"-->
<!--                                       onclick="CusOrderDetails()">-->
<!--                                <label class="form-check-label" for="firstRadio">Order Details</label>-->
<!--                            </li>-->
<!--                            <li class="list-group-item border border-0">-->
<!--                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value=""-->
<!--                                       id="secondRadio">-->
<!--                                <label class="form-check-label" for="secondRadio">My Returns</label>-->
<!--                            </li>-->
<!--                            <li class="list-group-item border border-0">-->
<!--                                <input class="form-check-input me-1" type="radio" name="mangeAccountRadio" value=""-->
<!--                                       id="thirdRadio">-->
<!--                                <label class="form-check-label" for="thirdRadio">My Cancellations</label>-->
<!--                            </li>-->
                        </ul>
                    </div>


<!--                    <p class="text-black-50 fw-bolder fs-6 ">My Reviews</p>-->
<!--                    <p class="text-black-50 fw-bolder fs-6 ">My Wishlist</p>-->
                </div>


            </div>
        </div>

        <div class="col-lg-9 col-md-9 col-sm-12">

            <div class="" id="cus_component">


            </div>

        </div>
    </div>

</div>
<script>
    Cus_Dashboard_Component();
</script>



<?php
include_once ('../component/footer.php');
?>

</body>
