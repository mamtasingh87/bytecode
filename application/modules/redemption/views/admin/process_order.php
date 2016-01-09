<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>">Processed Order </h1>
    </div>
    <div class="content">
        <table class="list">
            <tr>
                <th>Name:</th>
                <td><?php echo (isset($first_name) ? $first_name : '' ). ' ' .(isset($last_name) ? $last_name : '')  ?></td>
            </tr>
            <tr>
                <th>Email:</th>
                <td><?php echo isset($email) ? $email : ''; ?></td>
            </tr>
            <tr>
                <th>Phone:</th>
                <td><?php echo isset($phone) ? $phone : ''; ?></td>
            </tr>
            <tr>
                <th>Shipping Address:</th>
                <td><?php echo isset($address) ? $address : ''; ?></td>
            </tr>
            <tr>
                <th>City:</th>
                <td><?php echo isset($city) ? $city : ''; ?></td>
            </tr>
            <tr>
                <th>Zip:</th>
                <td><?php echo isset($zip) ? $zip : ''; ?></td>
            </tr>
            <tr>
                <th>Points:</th>
                <td><?php echo isset($points) ? $points : ''; ?></td>
            </tr>
            <tr>
                <th>Order Number:</th>
                <td><?php echo isset($order_number) ? $order_number : ''; ?></td>
            </tr>
            <tr>
                <th>Ordered On</th>
                <td><?php echo isset($ordered_on) ? $ordered_on : ''; ?></td>
            </tr>
            <tr>
                <th>Shipping On</th>
                <td><?php echo isset($shipped_on) ? $shipped_on : ''; ?></td>
            </tr>
            <tr>
                <th>Shipping Notes</th>
                <td><?php echo isset($shipping_notes) ? $shipping_notes : ''; ?></td>
            </tr>
            <tr>
                <th>Notes</th>
                <td><?php echo isset($notes) ? $notes : ''; ?></td>
            </tr>
            <tr>
                <th>Total Amount</th>
                <td><?php echo isset($total) ? $total : ''; ?></td>
            </tr>
            <tr>
                <th>Status</th>
                <?php $status = ($status == 1 ? 'Pending' : ($status == 2 ? 'Shipped' : ($status == 3 ? 'Delivered' : ($status == 4 ? 'Cancelled' : 'Processed')))) ?>
                <td><?php echo isset($status) ? $status : ''; ?></td>
            </tr>

            <?php // if ($data['request_document']) { ?>
<!--                <tr>
        <th>Attached Document</th>
        <td><a title="My account" href="<?php // echo site_url('users/account/download/quote/' . $data['request_document']);  ?>" class="btn btn-primary"><i class="fa fa-download"></i> Download </a></td>
    </tr>-->
            <?php // } ?>
        </table>
    </div>
</div>
