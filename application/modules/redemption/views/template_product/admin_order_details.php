<table border="0" cellpadding="5" cellspacing="5" style="width:500px;">
    <?php if (!empty($orders)): ?>
        <tr>
            <td>
                <table border="0" cellpadding="5" cellspacing="5" style="width:500px;">
                    <tr>
                        <td>Ordered By:</td>
                        <td><?php echo $uname ?></td>
                    </tr>
                    <tr>
                        <td>Order Number:</td>
                        <td><?php echo $orders['order_number'] ?></td>
                    </tr>
                    <tr>
                        <td>Ordered On:</td>
                        <td><?php echo (strtotime($orders['ordered_on']) > 0) ? date('Y M, d', strtotime($orders['ordered_on'])) : '' ?></td>
                    </tr>
<!--                    <tr>
                        <td>Shipped On:</td>
                        <td><?php echo (strtotime($orders['shipped_on']) > 0) ? date('Y M, d', strtotime($orders['shipped_on'])) : 'Not Shipped' ?></td>
                    </tr>-->
                    <tr>
                        <td>Total Amount:</td>
                        <td><?php echo '$'.$orders['total'] ?></td>
                    </tr>
                </table>
            </td>
        </tr>
    <?php endif; ?>
    <?php if (!empty($products)): ?>
        <tr>
            <td>
                <table border="1" cellpadding="5" cellspacing="5" style="width:500px;border-collapse: collapse">
                    <thead>
                        <tr>                
                            <td>Image</td>
                            <td>Name</td>
                            <td>Sku</td>
                            <td>Slug</td>
                            <!--<td>Price</td>-->
                            <td>Quantity</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td>
                                    <?php
                                    $images = $this->load->model('products_model')->getProductImages($product['product_id']);
                                    if ($images) {
                                        $image = '<img src="' . base_url() . 'uploads/product_images/' . $images[0]->pimage . '" width="50" height="50">';
                                    } else {
                                        $image = '<img src="' . base_url() . 'uploads/product_images/no_picture.png" width="50" height="50">';
                                    }
                                    echo $image;
                                    ?>                        
                                </td>
                                <td><?php echo $product['name'] ?></td>
                                <td><?php echo $product['sku'] ?></td>
                                <td><?php echo $product['slug'] ?></td>
                                <!--<td><?php // echo '$'.$product['price'] ?></td>-->
                                <td><?php echo $product['quantity'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </td>
        </tr>
    <?php endif; ?>
</table>
<?php if (isset($shipping) && !empty($shipping)): ?>
<h3>This order will be shipped to:</h3>
<div>
    <p><?php echo $shipping['firstname'].' '.$shipping['lastname'] ?></p>
    <p><?php echo $shipping['address'].','.$shipping['zip'] ?></p>
</div>
<?php endif; ?>


<?php if (isset($credit_data) && !empty($credit_data)): ?>
<table border="1" cellpadding="5" cellspacing="5" style="width:500px;border-collapse: collapse">
    <tr>
        <td>Amount Credited</td>
        <td><?php echo '$'.$orders['total'] ?></td>
    </tr>
    <tr>
        <td>Point Credited</td>
        <td><?php echo $credit_data['earn_point'] ?></td>
    </tr>
</table>
<?php endif; ?>
