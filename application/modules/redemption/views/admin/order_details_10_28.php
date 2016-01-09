<?php // echo '<pre>'; print_r($request_data);exit;   ?>
<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Order Details </h1>
    </div>
    <div class="content">
        <table class="list">
            <tr>
                <th>Name:</th>
                <td><?php echo (isset($request_data['first_name']) ? $request_data['first_name'] : '' ) . ' ' . (isset($request_data['last_name']) ? $request_data['last_name'] : '') ?></td>
            </tr>
            <tr>
                <th>Order Number:</th>
                <td><?php echo isset($request_data['order_number']) ? $request_data['order_number'] : ''; ?></td>
            </tr>
            <tr>
                <th>Ordered On</th>
                <td><?php echo isset($request_data['ordered_on']) ? $request_data['ordered_on'] : ''; ?></td>
            </tr>
            <tr>
                <th>Shipping On</th>
                <td><?php echo isset($request_data['shipped_on']) ? $request_data['shipped_on'] : ''; ?></td>
            </tr>

            <tr>
                <th>Total Amount</th>
                <td>$<?php echo isset($request_data['total']) ? $request_data['total'] : ''; ?></td>
            </tr>

        </table>
    </div>
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Product Details </h1>
    </div>


    <div class="content">
        <table class="list">
            <thead>
                <tr>
                    <th class="left">Image</th> <th class="left">Name</th><th class="left">Sku</th><th class="left">Slug</th><th class="left">Quantity</th><th class="left">Excerpt</th><th class="left">Price</th>

                </tr>
            </thead>
            <?php foreach ($order_data as $values): ?>
                <tr>
                    <?php
                    $images = $this->load->model('products_model')->getProductImages($values['product_id']);
                    if ($images) {
                        $image = '<img src="' . base_url() . 'uploads/product_images/' . $images[0]->pimage . '" width="100" height="100">';
                    } else {
                        $image = '<img src="' . base_url() . 'uploads/product_images/no_picture.png" width="100" height="100">';
                    }
                    ?>
                    <td><?php echo $image; ?></td>
                    <td><?php echo (isset($values['name']) ? $values['name'] : '' ); ?></td>
                    <td><?php echo isset($values['sku']) ? $values['sku'] : ''; ?></td>
                    <td><?php echo isset($values['slug']) ? $values['slug'] : ''; ?></td>
                    <td><?php echo isset($values['quantity']) ? $values['quantity'] : ''; ?></td>
                    <td><?php echo isset($values['excerpt']) ? $values['excerpt'] : ''; ?></td>
                    <td>$<?php echo isset($values['price']) ? $values['price'] : ''; ?></td>
                </tr> 
            <?php endforeach; ?>
        </table>
    </div>



    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Status </h1>
    </div>
    <div class="content">
        <?php echo form_open(ADMIN_PATH . '/redemption/order/changeorderstatus/'); ?>
        <input type="hidden" name="request_id" value="<?php echo $request_id; ?>">
        <table class="list">
            <div class="buttons">
                <select id="do" name="status">
                    <option value="">Select Status</option>
                    <option value="1">Pending</option>
                    <option value="2">Shipped</option>
                    <option value="3">Delivered</option>
                    <option value="4">Cancelled</option>
                    <option value="5">Processed</option>
                </select>
                <button type="submit" class="button changestatus" ><span>Change Status</span></button>
            </div>
            <tr>
                <th>Status</th>
                <?php $status = ($request_data['status'] == 1 ? 'Pending' : ($request_data['status'] == 2 ? 'Shipped' : ($request_data['status'] == 3 ? 'Delivered' : ($request_data['status'] == 4 ? 'Cancelled' : 'Processed')))) ?>
                <td><?php echo isset($status) ? $status : ''; ?></td>
            </tr>

        </table>
        <?php echo form_close(); ?>
    </div>


    <?php if (!empty($order_log)): ?>
        <div class="heading">
            <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Order Log </h1>
        </div>
        <div class="content">
            <table class="list">
                <thead>                
                    <tr>
                        <th>Status</th>
                        <th>Change on</th>
                    </tr>            
                </thead>
                <tbody>
                    <?php foreach ($order_log as $o_data): ?>
                    <?php $status = ($o_data['status'] == 5 ? 'Processed' : ($o_data['status'] == 4 ? 'Cancelled' : ($o_data['status'] == 3 ? 'Delivered' : ($o_data['status'] == 2 ? 'Shipped' : 'Pending')))); ?>
                        <tr>
                            <td><?php echo $status ?></td>
                            <td><?php echo (strtotime($o_data['change_on'])>0)?date('d M,Y h:i a',  strtotime($o_data['change_on'])):'' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>


</div>
