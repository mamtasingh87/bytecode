<?php if (isset($errorMessage) && $errorMessage) {
    echo $errorMessage;
} ?>
<?php echo $this->session->flashdata('product_cart_message'); ?>
<span id="return_msg"></span>
<?php if (count($cart_items) > 0): ?>
    <?php $grand_total = 0.00; ?>
    <div class="col-nest">
        <!--cart view-->

        <div data-cols="1/3" class="col">
            <div id="orderSummary">
                <div class="cartSummary">
                    <div class="cartSummaryTitle sub-inner-head">Your Cart Summary</div>

                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>            
                                    <th class="text-left">Product Image</th>
                                    <th class="text-left">Product Name</th>
                                    <th>SKU</th>
                                    <th>Qty</th>
                                    <th>Price </th>
                                    <th>Subtotal</th>
                                    <th>Remove</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php foreach ($cart_items as $cart_values): ?>
        <?php $price = ($cart_values['product_saleprice'] > 0) ? $cart_values['product_saleprice'] : $cart_values['product_price']; ?>
                                            <?php $product_total = $cart_values['product_qty'] * $price ?>
                                    <tr>            
                                        <td>
                                            <?php
                                            $images = $this->load->model('products_model')->getProductImages($cart_values['product_id']);
                                            if ($images) {
                                                $image = '<img src="' . base_url() . 'uploads/product_images/' . $images[0]->pimage . '" width="100" height="100">';
                                            } else {
                                                $image = '<img src="' . base_url() . 'uploads/product_images/no_picture.png" width="100" height="100">';
                                            }
                                            ?>
        <?php echo $image; ?>
                                        </td>
                                        <td class="text-left"><?php echo $cart_values['product_name'] ?></td>
                                        <td><?php echo $cart_values['product_sku'] ?></td>
                                        <td>
                                            <span class="product_qty"><?php echo $cart_values['product_qty'] ?></span>
                                            <span style="display: none;" class="product_text"><input type="text" value="<?php echo $cart_values['product_qty'] ?>" class="form-control" name="edit_qty"/></span>
                                            <i onclick="edit_qty(this)" class="fa fa-pencil"></i>
                                            <i style="display: none;" onclick="update_qty(this,<?php echo $cart_values['product_id'] ?>,<?php echo $cart_values['id'] ?>)" class="fa fa-check"></i>
                                        </td>
                                        <td><?php echo $price ?></td>
                                        <td><?php echo number_format($product_total, 2) ?></td>
                                        <td class="text-center"><a style="cursor:pointer" onclick="updateItem(<?php echo $cart_values['id'] ?>);" class="text-red remove "><i class="fa fa-close"></i></a></td>
                                        <!--<td><a style="cursor:pointer" onclick="updateItem(<?php // echo $cart_values['product_id']      ?>);" class="text-red remove"><i class="fa fa-times"></i></a></td>-->
                                    </tr>                    

        <?php $grand_total = $grand_total + $product_total; ?>
    <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>



                    <div class="cartSummaryTotals">
                        <div class="col-nest">
                            <div data-small-cols="2/3" data-medium-cols="2/3" data-cols="2/3" class="col total-price">
                                <div class="cartSummaryTotalsKey">Grand Total:</div>
                            </div>
                            <div data-small-cols="1/3" data-medium-cols="1/3" data-cols="1/3" class="col total-price">
                                <div class="cartSummaryTotalsValue"><?php echo number_format($grand_total, 2) ?></div>
                            </div>
                        </div>
                    </div>


                </div>


            </div>
        </div>

        <!--cart end-->
        <div data-cols="2/3" class="col">

            <div class="checkoutAddress"><div id="addressFormWrapper">
                    <div class="page-header">
                        <h3>Checkout</h3>
                    </div>

                    <div class="alert red hide" id="addressError"></div>
                    <div class="content-box">       
                        <form accept-charset="utf-8" method="post" id="addressForm" action="<?php echo site_url('redemption/redeem/orderplaced') ?>">
                            <input type="hidden" name="total" value="<?php echo $grand_total ?>"/>
                            <legend><input type="checkbox" onclick="set_same_as_profile(this)" name="is_profile_address" value="1" <?php echo (!empty($is_profile_address)) ? 'checked="checked"' : ''; ?> id="same_as_profile"/><label>Same as Profile Address</label></legend>

                            <div class="content-box-in"> 
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/2" data-medium-cols="1/2" data-cols="1/2" class="col">
                                                <label class="required">First Name</label>
                                                <input type="text" value="<?php echo (!empty($firstname)) ? $firstname : ''; ?>" name="firstname" class="form-control">
    <?php echo form_error('firstname'); ?>
                                            </div>                       
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/2" data-medium-cols="1/2" data-cols="1/2" class="col">
                                                <label class="required">Last Name</label>
                                                <input type="text" value="<?php echo (!empty($lastname)) ? $lastname : ''; ?>" name="lastname" class="form-control">
    <?php echo form_error('lastname'); ?>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/2" data-medium-cols="1/2" data-cols="1/2" class="col">
                                                <label class="required">Email</label>
                                                <input type="text" value="<?php echo (!empty($email)) ? $email : ''; ?>" name="email" class="form-control">
    <?php echo form_error('email'); ?>
                                            </div>                        
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/2" data-medium-cols="1/2" data-cols="1/2" class="col">
                                                <label class="required">Phone</label>
                                                <input type="text" value="<?php echo (!empty($phone)) ? $phone : ''; ?>" name="phone" class="form-control">
    <?php echo form_error('phone'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-cols="1" class="col">
                                                <label class="required">Address</label>
                                                <input type="text" value="<?php echo (!empty($address)) ? $address : ''; ?>" name="address" class="form-control">
    <?php echo form_error('address'); ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/3" data-medium-cols="2/5" data-cols="2/5" class="col">
                                                <label class="required">City</label>
                                                <input type="text" value="<?php echo (!empty($city)) ? $city : ''; ?>" name="city" class="form-control">
    <?php echo form_error('city'); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/3" data-medium-cols="2/5" data-cols="2/5" class="col">
                                                <label>State</label>
                                                <span class="select-span">
                                                <select id="zone_id" name="state_id" class="form-control">
                                                    <?php foreach ($states as $stateKey => $stateValue): ?>
                                                        <option value="<?php echo $stateKey ?>" <?php echo (!empty($state_id) && $state_id == $stateKey) ? 'selected="selected"' : ''; ?>><?php echo $stateValue ?></option>
                                                <?php endforeach; ?>
                                                </select>
                                                </span>
    <?php echo form_error('state_id'); ?>
                                            </div>   
                                        </div>
                                    </div> 
                                    <div class="col-sm-6">
                                        <div class="form-group">
                                            <div data-small-cols="1/3" data-medium-cols="1/5" data-cols="1/5" class="col">
                                                <label class="required">Zip</label>
                                                <input type="text" maxlength="10" value="<?php echo (!empty($zip)) ? $zip : ''; ?>" name="zip" class="form-control">
    <?php echo form_error('zip'); ?>
                                            </div>
                                        </div>   
                                    </div>   
                                </div>
                                <button type="submit" id="btn_cod" class="blue btn btn-primary">Submit Order</button>
                            </div>
                        </form>
                    </div>

                </div></div>

            </form>
        </div>

    </div>

    <script type="text/javascript">

        function update_qty(obj, prodId,cart_id) {
            var order_qty = jQuery(obj).prev().prev().children('input').val();
            if (order_qty >= 1 && order_qty != null && order_qty != '') {
                jQuery.ajax({
                    url: '<?php echo site_url('redemption/redeem/editcartqty') ?>',
                    data: {pid: prodId, oqty: order_qty,cart_id:cart_id},
                    success: function(res) {
                        var parsed_data = JSON.parse(res);
                        if (parsed_data.success) {
                            location.reload();
                        } else {
                            jQuery('#return_msg').html(parsed_data.msg)
                        }
                    }
                })
            } else {
                alert('Improper Qty value')
            }
        }

        function edit_qty(obj) {
            var parent_obj = jQuery(obj).parent('td');
            jQuery(obj).hide()
            jQuery(obj).prev().show();
            jQuery(obj).prev().prev().hide();
            jQuery(obj).next().show();
        }


        function set_same_as_profile(obj) {
            if (jQuery(obj).is(':checked')) {
                jQuery.ajax({
                    url: '<?php echo site_url('users/users/getuseraddress') ?>',
                    success: function(evt) {
                        var json_data = JSON.parse(evt);
                        if (json_data.success) {
                            var address_data = json_data.data;
                            jQuery('input[name=firstname]').val(address_data.first_name);
                            jQuery('input[name=lastname]').val(address_data.last_name);
                            jQuery('input[name=email]').val(address_data.email);
                            jQuery('input[name=phone]').val(address_data.phone);
                            jQuery('input[name=address]').val(address_data.address);
                            jQuery('input[name=city]').val(address_data.city);
                            jQuery('select[name=state_id]').val(address_data.state);
                            jQuery('input[name=zip]').val(address_data.zip);
                        }
                    }
                })
            } else {
                jQuery("#addressForm").closest('form').find("input[type=text], select").val("");

            }
        }

        function updateItem(prodId) {
            jQuery.ajax({
                url: '<?php echo site_url('redemption/redeem/removeproduct') ?>',
                data: {prod_id: prodId},
                success: function(evt) {
                    location.reload();
                }
            })
        }
    </script>
<?php else: ?>
    <h2 class="cart-empty">Shopping cart is empty.</h2>
<?php endif; ?>
