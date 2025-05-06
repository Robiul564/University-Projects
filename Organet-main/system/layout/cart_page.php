<?php
$TITLE = "MealMate";
include_once '../header/header.php';
@$user_type = $_SESSION['user_type'];

include_once '../../helper/db.connection.php';

$db = $mm_conn;
if (!$db) {
    echo "not connected";
}
if (isset($_SESSION['id'])) {

    $id = $_SESSION['id'];
    $sql = "SELECT food_cart.*, products.*
                  FROM food_cart
                  JOIN products ON food_cart.food_id = products.id
                  WHERE food_cart.user_id = $id";

    $result = mysqli_query($db, $sql);
//    print_r($result);
} else {
    echo "ID not set in the URL.";
}


?>
<body class="h-100">

<?php
include_once '../component/navbar.php';
?>

<div id="toast">
    <div id="desc"></div>
</div>


<div class="container mt-5">
    <div class="row">
        <div class="col-lg-8">


            <?php
            $id = $_SESSION['id'];
            $sql = "SELECT user_id,food_id,qty,food_cart.price 
                    AS cart_price,products.*   
                    FROM food_cart
                    JOIN products ON food_cart.food_id = products.id

                    WHERE food_cart.user_id = $id";

            $result = mysqli_query($db, $sql);
//        if (mysqli_fetch_assoc($result)==null)
//        {
//            echo "<div class='text-center'>  <h3>The cart is empty</h3> </div>";
//        }else{
            while ($data = mysqli_fetch_assoc($result)) {
            ?>
                <div class="card mb-3">
                    <div class="list-group list-group-flush">

                        <div class="list-group-item d-flex align-items-center">

                            <img src="../../uploads/img/<?php echo $data['img']; ?>" class="img-fluid me-3" alt="Item"
                                 style="height: 100px;width: 100px">
                            <div class="flex-grow-1">
                                <a href="#" class="d-block fw-bold"><?php echo $data['title']; ?></a>
                                <small class="text-muted">MHN, Color family: Multicolor</small>
                            </div>
                            <div class="text-end me-3">
                                <p class="mb-0">Num Of Products: <?php echo $data['qty']; ?></p>
                                <p class="mb-0 text-success">Total price: $<?php echo $data['cart_price']; ?></p>
                            </div>
                            <div class="d-flex flex-column">
                                <button class="btn btn-outline-success mb-2 w-100 p-1 px-2" type="button"
                                        onclick="AddToCalculate('<?php echo $data['title']; ?>','<?php echo $data['qty']; ?>','<?php echo $data['cart_price']; ?>','<?php echo $data['food_id']; ?>')">
                                    Order<i class="bi bi-bag-plus ms-1 fs-5"></i></button>
                                <button class="btn btn-outline-danger w-100 p-2" onclick="DeleteFromCart('<?php echo $data['food_id']; ?>')"><i class="bi bi-trash"></i></button>
                            </div>
                        </div>
                    </div>
                </div>

                <?php
            }
            ?>
        </div>


        <div class="col-lg-4 " style="top: 150px;">
            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title fw-bold">Location</h5>
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-geo-alt me-2"></i>
                            <div>
                                <div>Pirojpur Sadar, Pirojpur, Barishal</div>
                                <a href="#" class="btn btn-link p-0">Change</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-body">
                    <h5 class="card-title mb-2 fw-bold">Order Summary</h5>
                    <div>
                        <p class="text-black-50 fw-bolder fs-6 px-3">Name--------------Qnty----Price----Action</p>
                    </div>
                    <div id="invoice_product_table">

                    </div>


                    <div class="mt-5 border-top">
                        <div class="d-flex justify-content-between mb-2 mt-1">
                            <div><p class="fw-bold ">Subtotal</p></div>
                            <div><p id="total" class="fw-normal fs-5 mb-0"></p></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <div>Delivery Charge</div>
                            <div><p id="delivery_charge" class="fw-normal fs-5 mb-0">50</p></div>
                        </div>

                        <div class="input-group mb-3">
                            <input type="text" class="form-control" id="voucher_code" placeholder="Enter Voucher Code">
                            <span class="input-group-text p-0 ms-2 border-0 "><button
                                        class="input-group-text btn btn-primary w-100"
                                        id="voucher_apply_btn">Apply</button></span>


                        </div>

                        <div class="d-flex justify-content-between mb-2">
                            <div>Discount</div>
                            <div><p id="discount_count" class="fw-normal fs-5 mb-0">0</p></div>
                        </div>
                        <div class="d-flex justify-content-between mb-2">

                            <div><p class="fw-bold ">Total</p></div>
                            <div><p id="payable" class="fw-bold fs-3"></p></div>
                        </div>
                        <button class="btn btn-success w-100" type="button" onclick="CreateOrder()">Proceed to Checkout
                            (0)
                        </button>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>




<script>
    async function DeleteFromCart(id) {
        try {
            let res = await fetch('../../api/mm_deleteFromCart.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: new URLSearchParams({
                    'x': id
                })
            });

            if (res.ok) {
                let data = await res.json();
                if (data.statusCode === 6) {
                    successToast(data.message);
                    console.log(data);
                    window.location.reload();
                } else {
                    errorToast(data.message);
                }
            } else {
                errorToast("An error occurred. Please try again.");
            }
        } catch (error) {
            console.error("Error:", error.message);
            errorToast("Something went wrong while deleting the item.");
        }
    }




    let InvoiceItem = [];

    function AddToCalculate(PName, PQty, PPrice, Pid) {
        if (Pid.length === 0 || PName.length === 0 || PPrice.length === 0 || PQty.length === 0 || PQty === 0) {
            errorToast('Please give the required value');
        } else {
            let TotalPrice;
            if(PQty>1){
                TotalPrice = parseFloat(PPrice).toFixed(2);
            }else {
               TotalPrice = (parseFloat(PPrice) * parseFloat(PQty)).toFixed(2);
            }

            let item = {
                product_name: PName,
                product_id: Pid,
                qty: PQty,
                sale_price: TotalPrice
            };
            InvoiceItem.push(item);
             ShowInvoiceItem();
        }
    }

    async function ShowInvoiceItem() {
        let invoiceList = document.getElementById('invoice_product_table');
        invoiceList.innerHTML = '';

        InvoiceItem.forEach(function (item, index) {
            let EachItem = `
            <div class="card mb-2 px-2 py-1 bg-success-subtle">
                <div class="row">
                    <div class="col-6">
                        <h6 class="card-title mt-1">${item['product_name']}</h6>
                    </div>
                    <div class="col-1">
                        <p class="card-text">${item['qty']}</p>
                    </div>
                    <div class="col-3">
                        <p class="card-text">$${item['sale_price']}</p>
                    </div>
                    <div class="col-2 text-end">
                        <button data-index="${index}" class="removeItemBtn btn btn-outline-danger"><i class="bi bi-trash"></i></button>
                    </div>
                </div>
            </div>
        `;
            invoiceList.insertAdjacentHTML('beforeend', EachItem);
        });

        await CalculateFinalInvoice();

        document.querySelectorAll('.removeItemBtn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                let index = btn.getAttribute('data-index');
                RemoveItem(index);
            });
        });
    }

    async function RemoveItem(index) {
        InvoiceItem.splice(index, 1);
        await ShowInvoiceItem();
    }


    async function CalculateFinalInvoice() {
        let Total = 0;
        let Payable = 0;

        let delivery_charge = document.getElementById('delivery_charge').innerText;

        InvoiceItem.forEach((item) => {
            Total += parseFloat(item['sale_price']);
        });
        let total_with_DC =Total+ parseFloat(delivery_charge);

        $('#voucher_apply_btn').on('click', async function () {

            let voucher_code = parseFloat(document.getElementById('voucher_code').value) || null;
            if (voucher_code !== null) {
                if (voucher_code === 1234) {
                    let discount = (Total * (15 / 100)).toFixed(2);
                    Payable = (Total - discount) + parseFloat(delivery_charge);

                    document.getElementById('discount_count').innerText = `${discount}`;
                    document.getElementById('payable').innerText = `${Payable}`;

                } else {
                    errorToast('Wrong Voucher code');
                }
            } else {
                errorToast('Please enter the code');
            }

        });

        document.getElementById('total').innerText = `${Total}`;
        if (Total !== null){
            document.getElementById('payable').innerText = `${total_with_DC}`;
        }else {
            document.getElementById('payable').innerText = `${Total}`;
        }

    }


    async function CreateOrder() {
        let total = document.getElementById('total').innerText;
        let delivery_charge = document.getElementById('delivery_charge').innerText;
        let payable = document.getElementById('payable').innerText;
        let discount = document.getElementById('discount_count').innerText || 0;

        let Data = {
            "total": total,
            "delivery_charge": delivery_charge,
            "discount": discount,
            "payable": payable,
            "products": InvoiceItem
        }



        if (InvoiceItem.length === 0) {
            errorToast("Product Required!")
        } else {

            try {
                let res = await axios.post('../../api/mm_createInvoice.php', Data, {
                    headers: {
                        'Content-Type': 'application/json'
                    }
                });

                if (res.status === 200) {
                    let data = res.data;
                    if (data.statusCode === 6) {
                        successToast(data.message);
                        console.log(data)
                        // document.getElementById(card_value).innerHTML = data.data;
                    } else {
                        console.log(data.message)
                        // alert(data.message);
                    }
                } else {
                    errorToast("Something is wrong");
                }
            } catch (error) {
                console.log(error.message);
            }
        }
        console.log(Data);
    }
</script>

<?php
//include_once ('../component/footer.php');
//?>
</body>