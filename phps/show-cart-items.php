<?php

//session_start();

include 'connect.php';


    $sql = 'SELECT * from cart WHERE userid="'.$_SESSION['userid'].'"';
    $total=0;

if ($res = mysqli_query($conn, $sql)) { 
    if (mysqli_num_rows($res) > 0) { 
                                    echo'<section class="ftco-section-md0 ftco-cart">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12 ftco-animate">
                                                        <div class="cart-list">   
                                                            <table class="table bg-light">
                                                            <thead class="thead-primary">
                                                            <tr class="text-center">

                                                                <th colspan="2">Product</th>
                                                                <th>Pet</th>
                                                                <th class="price">Price</th>
                                                                <th>Quantity</th>
                                                                <th class="action">Action</th>
                                                            </tr>
                                                        </thead>';
        
                while ($row=mysqli_fetch_array($res)) {
                        $product_id=$row['prodid'];
                        $product_qty=$row['qty'];
                        $fetch_from_products='SELECT animalid,name,company,mrp,imagename from products WHERE prodid="'.$product_id.'"';
                            if($fetch_products_res = mysqli_query($conn,$fetch_from_products)){
                                if(mysqli_num_rows($fetch_products_res)>0){
                                    $product_row=mysqli_fetch_array($fetch_products_res);
                                    $animal_id=$product_row['animalid'];
                                    $product_name=$product_row['name'];
                                    $product_company=$product_row['company'];
                                    $product_mrp=$product_row['mrp'];
                                    $product_image=$product_row['imagename'];

                                    $fetch_from_animals='SELECT name from animals WHERE animalid="'.$animal_id.'"';
                                        if($fetch_animals_res = mysqli_query($conn,$fetch_from_animals)){
                                            if(mysqli_num_rows($fetch_animals_res)>0){
                                                $animal_row=mysqli_fetch_array($fetch_animals_res);
                                                $animal_name=$animal_row['name'];
                                                echo'<tbody>
                                    <tr class="text-center  ftco-animate">  
                                        
                                        <td class="image-prod"><div class="img" style="background-image:url(images/product_images/'.$product_image.');"></div></td>
                                        
                                        <td class="product-name">
                                            <h3>'.$product_name.'</h3>
                                            <p>Comapany: '.$product_company.'</p>
                                        </td>
                                        
                                        <td class="price">
                                            <p>';
                                                echo $animal_name;
    
                                        echo' </p>
                                        </td>
                                        
                                        <td class="price">$'.$product_mrp.'</td>
                                        
                                        <td class="price">'.$product_qty.'</td>
                                        
                                        <td class="product-remove">
                                        <a href="#" onclick="javascript:delete_form_variables(\''.$row['prodid'].'\');" data-toggle="modal" aria-pressed="false" data-target="#deleteInventoryModal">
                                            <span class="ion-ios-close"></span>
                                        </a>
                                    </td>   
                                    </tr>
                                    
                                    </tbody>';
                                    $subtotal=$product_qty*$product_mrp;
                                    $total=$total+$subtotal + 40;
                                            }
                                        }
                                    
                                }
                            }
                                    
                
                }
                $_SESSION["total_amt"]=$total;
                                echo'
                                </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
                
                <section class="ftco-section ftco-cart">
			<div class="container">
				
    		<div class="row justify-content-end">
    			<div class="col col-lg-5 col-md-6 mt-5 cart-wrap ftco-animate bg-light">
    				<div class="cart-total mb-3">
    					<h3>Cart Totals</h3>
    					<p class="d-flex">
    						<span>Subtotal</span>
    						<span>$'.$subtotal.'</span>
    					</p>
    					<p class="d-flex">
    						<span>Delivery</span>
    						<span>$40</span>
    					</p>
    					<hr>
    					<p class="d-flex total-price">
    						<span>Total</span>
    						<span>$'.$total.'</span>
    					</p>
    				</div><form action="checkout">
                    <p class="text-center">
                    <input type="submit" class="btn btn-primary py-3 px-4" value="Proceed to checkout"></a></p>
					</form>
    			</div>
    		</div>
			</div>
		</section>
                
                
                
                ';

                        }else{
                                    echo '<section class="ftco-section ftco-cart">
                                            <div class="container">
                                                <div class="row">
                                                    <div class="col-md-12 ftco-animate">
                                                        <div class="cart-list">
                                                            <table class="table">
                                                                <thead class="thead-primary">
                                                                    <tr class="text-center">
                                                                        <th colspan="5">The cart is empty</th>
                                                                    </tr>
                                                                </thead>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </section>';
                        }
                
    }else { 
        echo '<section class="ftco-section ftco-cart">
        <div class="container">
            <div class="row">
                <div class="col-md-12 ftco-animate">
                    <div class="cart-list">
                        <table class="table">
                            <thead class="thead-primary">
                            <tr class="text-center">
                                <th colspan="5">There is a problem with the connection!</th>
                            </tr>
                        </thead>
                        </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>';
    } 

    if (isset($_GET['delete_product_id'])) {
        $delete_product_id = $_GET['delete_product_id'];
        $delete_query = 'DELETE FROM cart WHERE prodid="'.$delete_product_id.'" AND userid="'.$_SESSION['userid'].'"';
        mysqli_query($conn, $delete_query);
        exit;
    }

mysqli_close($conn); 


?> 

<script>
   
//     // Function to show the increment and decrement buttons and hide the "Edit" button
//     function incrementQuantity(productId) {
//         var quantityElement = document.getElementById('quantity-' + productId);
//         var quantity = parseInt(quantityElement.innerHTML);
//         quantity++;
//         quantityElement.innerHTML = quantity;
//         updateTotalAmount();
//     }
    
//     function decrementQuantity(productId) {
//   var quantityElement = document.getElementById('quantity-' + productId);
//   var quantity = parseInt(quantityElement.innerHTML);
//   if (quantity > 1) {
//     quantity--;
//     quantityElement.innerHTML = quantity;
//     updateTotalAmount();
//   }
// }

// function updateTotalAmount() {
//   var totalAmountElement = document.getElementById('total-amount');
//   var totalAmount = 0;

//   // Loop through all the product rows
//   var productRows = document.getElementsByClassName('product-row');
//   for (var i = 0; i < productRows.length; i++) {
//     var quantityElement = productRows[i].querySelector('.quantity span');
//     var priceElement = productRows[i].querySelector('.price');
//     var quantity = parseInt(quantityElement.innerHTML);
//     var price = parseFloat(priceElement.innerHTML.replace('$', ''));
//     var subtotal = quantity * price;
//     totalAmount += subtotal;
//   }

//   totalAmountElement.innerHTML = '$' + totalAmount.toFixed(2);
// }

// document.addEventListener('DOMContentLoaded', function() {
//   updateTotalAmount();
// });
    
//     function showEditButtons(productId) {
//         var editButtonsElement = document.getElementById("edit-buttons-" + productId);
//         editButtonsElement.style.display = "block";
//     }

</script>