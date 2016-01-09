<div class="order-wrap">
    <h2 class="sub-inner-head">Order Details</h2>

    <div class="box">
        <div class="box-header">

            <div class="box-tools">
                <?php echo form_open('/redemption/order/showlist'); ?>
                <div class="input-group">
                    <input type="text" placeholder="Search" class="form-control input-sm pull-right" name="table_search" value="<?php echo ($search != "") ? $search : ""; ?>">
                    <div class="input-group-btn">
                        <button class="btn btn-sm btn-default" name="search" type="submit"><i class="fa fa-search"></i></button>
                    </div>
                </div>
                <?php echo form_close(); ?>
            </div>
        </div>
        <div class="box-body table-responsive">
            <table class="table table-hover">
                <tr>
                    <th>Order Number</th>
                    <th>Order Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
                    <th colspan="2">Action</th>
                </tr>

                <?php if ($orderData->order_number) { ?>
                    <?php foreach ($orderData as $order) { ?>
                        <tr>
                            <td><?php echo $order->order_number; ?></td>
                            <td><?php echo date('d M,Y h:i a', strtotime($order->ordered_on)); ?></td>
                            <td>$<?php echo $order->total; ?></td>
                            <td><?php echo $orderstatus[$order->status]; ?></td>
                            <td>
                                <a href="javascript:void(0);" class="vieworder" rel="<?php echo $order->id; ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a>

                            </td>
                            <?php if ($order->status == 2) { ?>
                                <td>
                                    <a type="button" class="view-shipping" data-toggle="modal" rel="<?php echo $order->id; ?>" data-target="#confirmModal" onclick="setOrderId(this)"><i class="fa fa-thumbs-up"></i></a>
                                </td>
                            <?php } ?>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr>
                        <td colspan="5">No Orders Placed!</td>
                    </tr>
                <?php } ?>
            </table>

        </div>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($orderData->exists()) ? $orderData->paged->current_row + 1 : 0; ?> to <?php echo $orderData->paged->current_row + $orderData->paged->items_on_page; ?> of <?php echo $orderData->paged->total_rows; ?> (<?php echo $orderData->paged->total_pages; ?>  Pages)</div>
        </div>
    </div>
    <!-- Modal -->
    <div id="confirmModal" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Delivery confirmation</h4>
                </div>
                <div class="modal-body">
                    <p>Is the product delivered to you?</p>
                </div>
                <div class="modal-footer">
                    <input type="hidden" id="order-id" value="0" />
                    <button type="submit" class="btn btn-primary" data-dismiss="modal" onclick="approveDelivery()">Yes</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".vieworder").click(function() {
            var orderId = $(this).attr("rel");
            if (orderId) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('/redemption/order/vieworder'); ?>",
                    data: {orderId: orderId, "ajaxCall": true},
                    success: function(data) {
                        $(".modal-title").html('Order Details');
                        $(".modal-body").html(data);
                    }
                });
            }
        });
    });
    function setOrderId(data) {
        $('#order-id').val(data.rel);
    }
    function approveDelivery() {
        var orderId = $('#order-id').val();
        if (orderId) {
            $.ajax({
                type: "POST",
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