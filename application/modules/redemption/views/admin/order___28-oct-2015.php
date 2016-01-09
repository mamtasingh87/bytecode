<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Orders </h1>

<!--        <div class="buttons">
            <select id="do">
                <option value="">Select Status</option>
                <option value="1">Pending</option>
                <option value="2">Shipped</option>
                <option value="3">Delivered</option>
                <option value="4">Cancelled</option>
                <option value="5">Processed</option>
            </select>
            <a class="button changestatus" href="#"><span>Change Status</span></a>
        </div>-->
    </div>
    <div class="content">

        <form id="filter_form" method="POST" action="<?php echo site_url(ADMIN_PATH . '/redemption/order/'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="order_number" placeholder="Order Number" value="<?php echo isset($params['order_number']) ? $params['order_number'] : ''; ?>"/></td>
                        <td><input type="text" name="order_by" placeholder="Order By" value="<?php echo isset($params['order_by']) ? $params['order_by'] : ''; ?>"/></td>
                        <td><input type="text" name="ordered_on" placeholder="Ordered On" value="<?php echo isset($params['ordered_on']) ? $params['ordered_on'] : ''; ?>"/></td>
                        <td><input type="text" name="total" placeholder="Total Amount" value="<?php echo isset($params['total']) ? $params['total'] : ''; ?>"/></td>
                        <td>
                            <select name="status" >
                                <option value=''> Select Status </option>
                                <option value="1" <?php echo isset($params['status']) && $params['status'] === '1' ? 'selected="selected"' : ''; ?>>Pending</option>
                                <option value="2" <?php echo isset($params['status']) && $params['status'] === '2' ? 'selected="selected"' : ''; ?>>Shipped</option>
                                <option value="3" <?php echo isset($params['status']) && $params['status'] === '3' ? 'selected="selected"' : ''; ?>>Delivered</option>
                                <option value="4" <?php echo isset($params['status']) && $params['status'] === '4' ? 'selected="selected"' : ''; ?>>Cancelled</option>
                                <option value="5" <?php echo isset($params['status']) && $params['status'] === '5' ? 'selected="selected"' : ''; ?>>Processed</option>
                            </select> 
                        </td>
                        <td>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/redemption/order/') . '?reset=true'; ?>'"><span>Clear</span></button>
                        </td>
                    </tr>
                </thead>
            </table>
        </form>


        <?php echo form_open(null, 'id="form"'); ?>
        <table class="list">
            <thead>
                <tr>
                    <th width="1" class="center"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th class="center"><a rel="id" class="sortable" href="#">#ID</a></th>
                    <th><a rel="order_number" class="sortable" href="#">Order Number</a></th>
                    <th><a rel="order_by" class="sortable" href="#">Order By</a></th>
                    <th class="left">Ordered On</th>
                    <th class="left">Total Amount</th>
                    <th class="left">Status</th>
                    <th class="left">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requestsdata): ?>
                    <?php foreach ($requestsdata as $request): ?>
                        <?php if ($request->status == 5) {
                            $class = "row_highlight";
                        } else {
                            $class = "";
                        } ?>
                        <tr >
                            <td class="center <?php echo $class; ?>"><input type="checkbox" value="<?php echo $request->id ?>" name="selected[]" /></td>
                            <td class="center <?php echo $class; ?>"><?php echo $request->id; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->order_number; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->first_name . " " . $request->last_name; ?></td>                            
                            <td class="<?php echo $class; ?>"><?php echo $request->ordered_on; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->total; ?></td>
                            <td class="left <?php echo $class; ?>"> <?php
                                if ($request->status == 5) {
                                    echo 'Processed';
                                } else if ($request->status == 4) {
                                    echo 'Cancelled';
                                } else if ($request->status == 3) {
                                    echo 'Delivered';
                                } else if ($request->status == 2) {
                                    echo 'Shipped';
                                } else {
                                    echo 'Pending';
                                }
                                ?>
                            </td>
                            <td class="<?php echo $class; ?>"> [ <a href="<?php echo site_url(ADMIN_PATH . '/redemption/order/details/' . $request->id) ?>">View</a> ] </td>
                        </tr>
    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="9">No request have been added.</td>
                    </tr>
        <?php endif; ?>
            </tbody>
        </table>
<?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($requestsdata) ? $limit + 1 : 0; ?> to <?php echo $limit + count($requestsdata); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.sortable').click(function() {
            sort = $(this);

            if (sort.hasClass('asc'))
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/redemption/order/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/redemption/order/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=asc";
            }

            return false;
        });

<?php if ($sort = $this->input->get('sort')): ?>
            $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
<?php else: ?>
            $('a.sortable[rel="name"]').addClass('asc');
<?php endif; ?>

        $('.changestatus').click(function() {
            if (confirm('Are you sure you want to do this operation?'))
            {
                if ($('#do').val() != 0) {
                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/redemption/order/changestatus/') . '?'; ?>' + 'do=' + $('#do').val()).submit();
                }
            }
            else
            {
                return false;
            }
        });

    });
</script>
<?php js_end(); ?>