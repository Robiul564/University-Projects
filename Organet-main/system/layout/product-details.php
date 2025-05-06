<?php
$TITLE = "MealMate";
include_once '../header/header.php';
@$user_type = $_SESSION['user_type'];
include_once '../../helper/db.connection.php';

$db = $mm_conn;
if (!$db)
{
    echo "not connected";
}
if(isset($_GET['id'])){
    $id = $_GET['id'];

    $sql="SELECT * FROM products WHERE id= $id";
    $result=mysqli_query($db,$sql);
    $row = mysqli_fetch_assoc($result);
    if($result){
        $row = mysqli_fetch_assoc($result);
         print_r($row);
    }


} else {
    echo "ID not set in the URL.";
}
//$product_id = $_POST['id'];
//echo $product_id;

?>
<body class="">
<?php

include_once '../component/navbar.php';
$sql="SELECT * FROM products WHERE id= $id";
$result=mysqli_query($db,$sql);
$row = mysqli_fetch_assoc($result);
?>




<div class="container mt-5">
    <div class="row">
        <!-- Product Images -->
        <div class="col-md-6">
            <div id="productGallery" class="carousel slide" data-bs-ride="carousel">
                <div class="carousel-inner">
                    <div class="carousel-item active">
                        <img src="../../uploads/img/<?php echo $row['img'];?>" class="d-block w-100" alt="Product Image 1">
                    </div>
<!--                    <div class="carousel-item">-->
<!--                        <img src="../../uploads/img/IMG-Blog-668bdfc22bed20.85195354.jpg" class="d-block w-100" alt="Product Image 2">-->
<!--                    </div>-->
<!--                    <div class="carousel-item">-->
<!--                        <img src="https://superfood.qodeinteractive.com/wp-content/uploads/2016/12/tea2-2.jpg" class="d-block w-100" alt="Product Image 3">-->
<!--                    </div>-->
                </div>
<!--                <button class="carousel-control-prev" type="button" data-bs-target="#productGallery" data-bs-slide="prev">-->
<!--                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>-->
<!--                    <span class="visually-hidden">Previous</span>-->
<!--                </button>-->
<!--                <button class="carousel-control-next" type="button" data-bs-target="#productGallery" data-bs-slide="next">-->
<!--                    <span class="carousel-control-next-icon" aria-hidden="true"></span>-->
<!--                    <span class="visually-hidden">Next</span>-->
<!--                </button>-->
            </div>
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h2 class="mb-3 fw-bolder text-success"><?php echo $row['title'];?></h2>
            <p class="price mb-3"><span class="h4">$<?php echo $row['price'];?></span></p>
            <div class="star-rating mb-3">
                <span style="width:80%">Rated <strong>4.00</strong> out of 5 based on <span>1</span> customer rating</span>
            </div>
            <p class="mb-3"><?php echo $row['des'];?></p>

            <!-- Add to Cart Form -->

            <div class="container mt-5">
                <div class="d-flex align-items-center">
                    <form id="quantityForm" class="d-flex align-items-center me-2 mb-0">
                        <div class="input-group">
                            <button class="btn btn-outline-dark" type="button" id="decreaseBtn">-</button>
                            <input type="text" class="form-control text-center border-dark" id="quantityInput" style="width: 50px" value="1" min="1">
                            <button class="btn btn-outline-dark" type="button" id="increaseBtn">+</button>
                        </div>
                    </form>
                    <button id="addToCartBtn" type="button" class="btn btn-primary" onclick="AddToCart(<?php echo $row['id']; ?> ,<?php echo $row['price']; ?>)">Add to Cart</button>
                </div>
            </div>

            <script>
                document.addEventListener("DOMContentLoaded", function() {
                    const quantityInput = document.getElementById("quantityInput");
                    const decreaseBtn = document.getElementById("decreaseBtn");
                    const increaseBtn = document.getElementById("increaseBtn");

                    // Increase quantity
                    increaseBtn.addEventListener("click", function() {
                        quantityInput.value = parseInt(quantityInput.value) + 1;
                    });

                    // Decrease quantity
                    decreaseBtn.addEventListener("click", function() {
                        let currentValue = parseInt(quantityInput.value);
                        if (currentValue > 1) {
                            quantityInput.value = currentValue - 1;
                        }
                    });

                    // Handle Add to Cart button click
                    // document.getElementById("addToCartBtn").addEventListener("click", function() {
                    //     const quantity = quantityInput.value;
                    //     alert("Added " + quantity + " items to the cart!");
                    // });
                });

                async function AddToCart(productId, productPrice) {
                    const quantityInput = document.getElementById("quantityInput");
                    const quantity = quantityInput.value;

                    let calculatePrice=quantity*productPrice;

                    let formData = new FormData();
                    formData.append('id', productId);
                    formData.append('price', calculatePrice);
                    formData.append('qty', quantity);
                    try {
                        let res = await axios.post('../../api/mm_addToCart.php', formData);

                        if (res.status === 200) {
                            let data = res.data;
                            if (data.statusCode === 6) {
                                successToast(data.message);
                                // document.getElementById(card_value).innerHTML = data.data;

                            } else {
                                alert(data.message);
                            }
                        } else {
                            errorToast("Something is wrong");
                        }
                    } catch (error) {
                        console.log(error.message);
                    }
                    // alert("Product ID: " + productPrice);
                }



            </script>


            <!-- Product Meta Information -->
<!--            <div class="mt-3">-->
<!--              -->
<!--                <span>Category: <a href="https://superfood.qodeinteractive.com/product-category/tea/" class="text-primary">Tea</a></span>-->
<!--            </div>-->

            <!-- Product Description & Additional Information -->
            <div class="accordion mt-4" id="productAccordion">
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingDescription">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseDescription" aria-expanded="true" aria-controls="collapseDescription">
                            Description
                        </button>
                    </h2>
                    <div id="collapseDescription" class="accordion-collapse collapse show" aria-labelledby="headingDescription" data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <h4>Description</h4>
                            <p>Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat . At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren.</p>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingAdditionalInfo">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseAdditionalInfo" aria-expanded="false" aria-controls="collapseAdditionalInfo">
                            Additional Information
                        </button>
                    </h2>
                    <div id="collapseAdditionalInfo" class="accordion-collapse collapse" aria-labelledby="headingAdditionalInfo" data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <h4>Additional Information</h4>
                            <table class="table">
                                <tbody>
                                <tr>
                                    <th>Weight</th>
                                    <td>1 kg</td>
                                </tr>
                                <tr>
                                    <th>Dimensions</th>
                                    <td>30 × 30 × 30 cm</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="accordion-item">
                    <h2 class="accordion-header" id="headingReviews">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseReviews" aria-expanded="false" aria-controls="collapseReviews">
                            Reviews (1)
                        </button>
                    </h2>
                    <div id="collapseReviews" class="accordion-collapse collapse" aria-labelledby="headingReviews" data-bs-parent="#productAccordion">
                        <div class="accordion-body">
                            <div id="reviews">
                                <h4 class="mb-3">1 review for Mock ups</h4>
                                <ul class="list-unstyled">
                                    <li class="d-flex mb-3">
                                        <img src="https://secure.gravatar.com/avatar/ba63672c2b70f02ec3d55c413d999040?s=60&amp;d=mm&amp;r=g" alt="" class="rounded-circle me-2" width="60" height="60">
                                        <div>
                                            <div class="star-rating">
                                                <span style="width:80%">Rated <strong>4</strong> out of 5</span>
                                            </div>
                                            <p class="mb-1"><strong>admin</strong> – <time datetime="2016-09-21T07:37:59+00:00">21 September, 2016</time></p>
                                            <p>Be the first</p>
                                        </div>
                                    </li>
                                </ul>
                                <!-- Review Form -->
                                <div id="review_form_wrapper">
                                    <div id="review_form">
                                        <h5 class="mb-3">Add a review</h5>
                                        <form action="" method="post">
                                            <div class="mb-3">
                                                <label for="rating" class="form-label">Your rating</label>
                                                <select name="rating" id="rating" class="form-select" required>
                                                    <option value="">Rate…</option>
                                                    <option value="5">Perfect</option>
                                                    <option value="4">Good</option>
                                                    <option value="3">Average</option>
                                                    <option value="2">Not that bad</option>
                                                    <option value="1">Very poor</option>
                                                </select>
                                            </div>
                                            <div class="mb-3">
                                                <label for="comment" class="form-label">Your review</label>
                                                <textarea id="comment" name="comment" class="form-control" rows="4" required></textarea>
                                            </div>
                                            <div class="mb-3">
                                                <label for="author" class="form-label">Name</label>
                                                <input id="author" name="author" type="text" class="form-control" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="email" class="form-label">Email</label>
                                                <input id="email" name="email" type="email" class="form-control" required>
                                            </div>
                                            <div class="mb-3 form-check">
                                                <input id="wp-comment-cookies-consent" name="wp-comment-cookies-consent" type="checkbox" class="form-check-input">
                                                <label for="wp-comment-cookies-consent" class="form-check-label">Save my name, email, and website in this browser for the next time I comment.</label>
                                            </div>
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</body>