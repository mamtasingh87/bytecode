<?php
//echo '<pre>';
//print_r($requestsdata);exit;
?>

<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Quote Requests </h1>

        <div class="buttons">
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>
        <div class="buttons">
            <a class="button changeautostatus" href="<?php echo site_url(ADMIN_PATH . '/quote/quote/exportfordialer'); ?>"><span>Export for dialer</span></a>
        </div>
        <div class="buttons">
            <select id="do">
                <?php foreach ($status as $k => $v) { ?>
                    <option value='<?php echo $k; ?>'><?php echo $v; ?></option>
                <?php } ?>
            </select>
            <a class="button changestatus" href="#"><span>Change Status</span></a>
        </div>
        <div class="buttons">
            <select id="doauto">
                <option value="">Send to dialer</option>
                <option value="1">Yes</option>
                <option value="2">No</option>
            </select>
            <a class="button changeautostatus" href="#"><span>Change Auto Dialer</span></a>
        </div>
    </div>
    <div class="content">

        <form id="filter_form" method="POST" action="<?php echo site_url(ADMIN_PATH . '/quote/quote/'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="client_name" placeholder="Client Name" value="<?php echo isset($params['client_name']) ? $params['client_name'] : ''; ?>"/></td>
                        <td><input type="text" name="requested_by" placeholder="Requested By" value="<?php echo isset($params['requested_by']) ? $params['requested_by'] : ''; ?>"/></td>
                        <td><input type="text" name="year_built" placeholder="Year Built" value="<?php echo isset($params['year_built']) ? $params['year_built'] : ''; ?>"/></td>
                        <td><input type="text" name="square_feet" placeholder="Square Feet" value="<?php echo isset($params['square_feet']) ? $params['square_feet'] : ''; ?>"/></td>
                        <td><input type="text" name="street_address" placeholder="Street Address " value="<?php echo isset($params['street_address']) ? $params['street_address'] : ''; ?>"/></td>
                        <td>
                            <select name="status" >
                                <?php foreach ($status as $k => $v) { ?>
                                    <option value='<?php echo $k; ?>' <?php echo isset($params['status']) && $params['status'] == $k ? 'selected="selected"' : ''; ?>><?php echo $v; ?></option>
                                <?php } ?>
                            </select> 
                        </td>
                        <td>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/quote/quote/') . '?reset=true'; ?>'"><span>Clear</span></button>
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
                    <th><a rel="client_first_name" class="sortable" href="#">Client Name</a></th>
                    <th><a rel="first_name" class="sortable" href="#">Requested By</a></th>
                    <th class="left">Year Built</th>
                    <th class="left">Square Feet</th>
                    <th class="left">Street Address</th>
                    <th class="left">Status</th>
                    <th class="left">Auto Dialer Status</th>
                    <!--<th class="left">Zoho ID</th>-->
                    <th class="left">Details</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($requestsdata): ?>
                    <?php foreach ($requestsdata as $request): ?>
                        <?php
                        if ($request->is_converted_binder == 1) {
                            $class = "row_highlight";
                        } else {
                            $class = "";
                        }
                        ?>
                        <tr >
                            <td class="center <?php echo $class; ?>"><input type="checkbox" value="<?php echo $request->id ?>" name="selected[]" /></td>
                            <td class="center <?php echo $class; ?>"><?php echo $request->id; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->client_first_name . ' ' . $request->client_middle_name . ' ' . $request->client_last_name; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->first_name . ' ' . $request->last_name; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->year_built; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->square_feet; ?></td>
                            <td class="<?php echo $class; ?>"><?php echo $request->street_address; ?></td>
                            <td class="<?php echo $class; ?> <?php echo str_replace(' ', '_', $status[$request->status]); ?>"><span><?php echo $status[$request->status]; ?></span></td>
                            <td class="left <?php echo $class; ?>"> <?php
                                if ($request->auto_dialer_status == 1) {
                                    echo 'Yes';
                                } else {
                                    echo 'No';
                                }
                                ?>
                            </td>
                            <!--<td class="<?php echo $class; ?>"><?php // echo $request->recordID;  ?></td>-->
                            <td class="<?php echo $class; ?>"> [ <a href="<?php echo site_url(ADMIN_PATH . '/quote/quote/details/' . $request->id) ?>">View</a> ]  <?php if ($request->status == 1 && $request->is_converted_binder != 1) { ?> [ <a href="<?php echo site_url(ADMIN_PATH . '/quote/quote/convert-binder/' . $request->id); ?>"> Convert Into Binder </a>]<?php } ?></td>
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
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/quote/quote/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/quote/quote/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=asc";
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
                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/quote/quote/changestatus/') . '?'; ?>' + 'do=' + $('#do').val()).submit();
                }
            }
            else
            {
                return false;
            }
        });
        $('.changeautostatus').click(function() {
            if (confirm('Are you sure you want to do this operation?'))
            {
                if ($('#doauto').val() != 0) {
                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/quote/quote/changeautostatus/') . '?'; ?>' + 'doauto=' + $('#doauto').val()).submit();
                }
            }
            else
            {
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
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/users/deletequote'); ?>').submit()
            }
            else
            {
                return false;
            }
        });

    });
</script>
<?php js_end(); ?>