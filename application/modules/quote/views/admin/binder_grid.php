<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> Binder Requests </h1>
        <div class="buttons">
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>
        <div class="buttons">
            <select class="dev-select-status" name="status">
                <?php foreach ($statusg as $key => $val) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $val ?></option>
                <?php } ?>
            </select>
            <a class="button dev-change" href="#"><span>Submit</span></a>
        </div>
    </div>
    <div class="content">
        <form id="filter_form" method="post" action="<?php echo site_url(ADMIN_PATH . '/quote/quote/binder'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="borrower_name" placeholder="Borrower Name" value="<?php echo isset($params['borrower_name']) ? $params['borrower_name'] : ''; ?>"/></th>
                        <th><input type="text" name="borrower_email" placeholder="Borrower Email" value="<?php echo isset($params['borrower_email']) ? $params['borrower_email'] : ''; ?>"/></th>
                        <th><input type="text" name="borrower_phone" placeholder="Phone" value="<?php echo isset($params['borrower_phone']) ? $params['borrower_phone'] : ''; ?>"/></th>
                        <th><input type="text" name="loan_number" placeholder="Loan No" value="<?php echo isset($params['loan_number']) ? $params['loan_number'] : ''; ?>"/></th>
                        <th><input type="text" name="requested_by" placeholder="Requested By" value="<?php echo isset($params['requested_by']) ? $params['requested_by'] : ''; ?>"/></th>
                        <th>
                            <select name="status">
                                <?php foreach ($filterstatus as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo isset($params['status']) && $params['status'] == $key ? 'selected="selected"' : ''; ?>><?php echo $val ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/quote/quote/binder') . '?reset=true'; ?>'"><span>Clear</span></button>
                        </th>
                    </tr>
                </thead>
            </table>
        </form>
        <?php echo form_open(null, 'id="form"'); ?>
        <input type="hidden" name="status" class="dev-input-status">
        <table class="list">
            <thead>
                <tr>
                    <th width="1" class="center"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th><a rel="qid" class="sortable" href="#">ID</a></th>
                    <th>Borrower Name</th>
                    <th>Borrower Email</th>
                    <th>Phone</th>
                    <th>Loan No</th>
                    <th>Requested By</th>
                    <th>Status</th>
                    <th>Zoho ID</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($binders) {
                    foreach ($binders as $binder) {
                        ?>
                        <tr>
                            <td class="center"><input type="checkbox" class="" value="<?php echo $binder->id ?>" name="selected[]" /></td>
                            <td><?php echo $binder->id; ?></td>
                            <td><?php echo $binder->borrower_name; ?></td>
                            <td><?php echo $binder->borrower_email; ?></td>
                            <td><?php echo $binder->borrower_phone; ?></td>
                            <td><?php echo $binder->loan_number; ?></td>
                            <td><?php echo $binder->first_name . ' ' . $binder->last_name; ?></td>
                            <td class="<?php echo str_replace(' ', '_', $status[$binder->status]); ?>"><span><?php echo isset($status[$binder->status]) ? $status[$binder->status] : 'NA'; ?></span></td>
                            <td><?php echo $binder->recordID; ?></td>
                            <td><a href="<?php echo site_url(ADMIN_PATH . '/quote/quote/binder-detail/') . '?id=' . $binder->id; ?>">[View]</a></td>
                        </tr>
                        <?php
                    }
                } else {
                    ?>
                    <tr><td colspan="9">No records were found in our database.</td></tr>
                <?php } ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($binders) ? $limit + 1 : 0; ?> to <?php echo $limit + count($binders); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>
<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.dev-select-status').change(function() {
            $('.dev-input-status').val($(this).val());
        });
        $('.dev-change').click(function() {
            if ($('.dev-input-status').val() != 0) {
                if (confirm('Are you sure? Perform this operation?'))
                {
                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/quote/quote/change-binder-status'); ?>').submit()
                }
                else
                {
                    return false;
                }
            } else
            {
                alert('Select status ');
                return false;
            }
        });
        $('.delete').click(function() {
            if (confirm('Delete cannot be undone! Are you sure you want to do this?'))
            {
//                if (confirm('Do you want to delete from ZOHO?')) {
//
//                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/users/deleteusertozoho'); ?>').submit()
//                }
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/users/deletebinder'); ?>').submit()
            }
            else
            {
                return false;
            }
        });
    });
</script>
<?php js_end(); ?>
