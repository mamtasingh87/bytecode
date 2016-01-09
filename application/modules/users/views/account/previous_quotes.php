<h2 class="sub-inner-head">Previous Quote Requests</h2>

<div class="box">
    <div class="box-header">

        <div class="box-tools">
            <?php echo form_open('/users/account/previous-quotes'); ?>
            <div class="input-group">
                <input type="text" placeholder="Search" class="form-control input-sm pull-right" name="table_search" value="<?php echo ($search != "") ? $search : ""; ?>">
                <div class="input-group-btn">
                    <button class="btn btn-sm btn-default" name="search" type="submit"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <?php echo form_close(); ?>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <tbody><tr>
                    <th>Client Name</th>
                    <th>Street Address</th>
                    <th>Year Built</th>
                    <th>Requested On</th>
                    <th>Square Feet</th>                      
                    <th>Status</th>                      
                    <th>Action</th>
                </tr>
                <?php
                if ($quotes->id) {
                    foreach ($quotes as $quote) {
                        ?>

                        <tr>
                            <td><?php echo $quote->client_first_name . ' ' . $quote->client_middle_name . ' ' . $quote->client_last_name; ?></td>
                            <td><?php echo $quote->street_address; ?></td>
                            <td><?php echo $quote->year_built; ?></td>
                            <td><?php echo date(DATE_FORMAT, strtotime($quote->requested_on)); ?></td>
                            <td><?php echo $quote->square_feet; ?></td>                      
                            <td class="<?php echo  str_replace(' ','_', $statuses[$quote->status]); ?>"><span ><?php echo $statuses[$quote->status]; ?></span></td>                      
                            <!--<td><?php // echo "Pending"; ?></td>-->                      
                            <td><a href="javascript:void(0);" class="view_quote" rel="<?php echo $quote->id; ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a></td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="6">No records were found in our database.</td></tr>
<?php } ?>
            </tbody></table>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($quotes->exists()) ? $quotes->paged->current_row + 1 : 0; ?> to <?php echo $quotes->paged->current_row + $quotes->paged->items_on_page; ?> of <?php echo $quotes->paged->total_rows; ?> (<?php echo $quotes->paged->total_pages; ?>  Pages)</div>
        </div>
    </div><!-- /.box-body -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".view_quote").click(function() {
            var viewID = $(this).attr("rel");
            if (viewID) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('/users/account/view-quotes'); ?>",
                    data: {viewID: viewID, "ajaxCall": true},
                    success: function(data) {
                        $(".modal-title").html('View Quote Request');
                        $(".modal-body").html(data);
                    }
                });
            }
        });
    });
</script>