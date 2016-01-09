<div class="heading">
<!--    <h1><img alt="" src="<?php // echo theme_url('assets/images/category.png');            ?>">Order Details </h1>-->
</div>
<div class="content">
    <dl>

        <dt>Name:</dt>
        <dd><?php echo (isset($request_data['first_name']) ? $request_data['first_name'] : '' ) . ' ' . (isset($request_data['last_name']) ? $request_data['last_name'] : ''); ?></dd>
        <dt>Order Number:</dt>
        <dd><?php echo isset($request_data['order_number']) ? $request_data['order_number'] : ''; ?></dd>
        <dt>Ordered On</dt>
        <dd><?php echo (isset($request_data['ordered_on']) && strtotime($request_data['ordered_on']) > 0) ? date(DATE_FORMAT, strtotime($request_data['ordered_on'])) : ''; ?></dd>
<!--        <dt>Shipping On</dt>
        <dd><?php // echo (isset($request_data['shipped_on']) && strtotime($request_data['shipped_on']) > 0) ? date('M dS, Y', strtotime($request_data['shipped_on'])) : 'Not Shipped'; ?></dd>-->
        <!--        <dt>Notes</dt>
                <dd><?php echo isset($request_data['notes']) ? $request_data['notes'] : ''; ?></dd>-->
        <dt>Total Amount</dt>
        <dd>$<?php echo isset($request_data['total']) ? $request_data['total'] : ''; ?></dd>
        <?php if ($request_data['status'] == 2) { ?>
            <dt>Order Delivered?</dt>
            <dd><button id="aprv-btn" onclick="approve()">Delivered</button></dd>
        <?php } ?>
    </dl>
</div>
<div class="heading">
    <h1>Product Details </h1>
</div>


<div class="content table-responsive">
<div class="table-in">
    <table class="table">
        <thead>
            <tr>
                <th>Image</th><th class="left">Name</th><th class="text-center">Sku</th><th class="">Slug</th><th class="">Price</th><th class="">Quantity</th>

            </tr>
        </thead>
        <?php foreach ($order_data as $values): ?>
            <tr>
                <?php
                $images = $this->load->model('products_model')->getProductImages($values['product_id']);
                if ($images) {
                    $image = '<img src="' . base_url() . 'uploads/product_images/' . $images[0]->pimage . '" width="50" height="50">';
                } else {
                    $image = '<img src="' . base_url() . 'uploads/product_images/no_picture.png" width="50" height="50">';
                }
                ?>
                <td><?php echo $image; ?></td>
                <td><?php echo (isset($values['name']) ? $values['name'] : '' ); ?></td>
                <td><?php echo isset($values['sku']) ? $values['sku'] : ''; ?></td>
                <td><?php echo isset($values['slug']) ? $values['slug'] : ''; ?></td>
                <td>$<?php echo isset($values['price']) ? $values['price'] : ''; ?></td>
                <td><?php echo isset($values['quantity']) ? $values['quantity'] : ''; ?></td>
            </tr> 
        <?php endforeach; ?>
    </table>
</div>
</div>
<script>
    function approve() {
        var orderId = <?php echo isset($request_data['id']) ? $request_data['id'] : ''; ?>;
        if (orderId) {
            $.ajax({
                method: "POST",
                url: "<?php echo site_url('/redemption/admin/order/changeorderstatus'); ?>",
                dataType: 'JSON',
                data: {request_id: orderId, status: 3, "ajaxCall": true},
                success: function(data) {
                    if (data.success) {
                        alert('Order Status successfully changed!!');
                        window.location.reload();
                    } else {
                        alert('Something went wrong. Please try again later!!');
                    }
                }
            });
        }
    }
</script>
