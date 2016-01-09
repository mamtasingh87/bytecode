<h2 class="sub-inner-head">Previous Binder Requests</h2>

<div class="box">
    <div class="box-header">

        <div class="box-tools">
            <?php echo form_open('/users/account/previous-binders'); ?>
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
                    <th>Borrower Name</th>
                    <th>Borrower Email</th>
                    <th>Requested On</th>
                    <th>Phone</th>
                    <th>Loan No</th>
                    <!--<th>Closing Date</th>-->
                    <th>Status</th>
                    <th>Action</th>
                </tr>
                <?php if ($binders->id) {
                    foreach ($binders as $binder) {?>
                        <tr>
                            <td><?php echo $binder->borrower_name; ?></td>
                            <td><?php echo $binder->borrower_email; ?></td>
                            <td><?php echo date(DATE_FORMAT, strtotime($binder->requested_on)); ?></td>
                            <td><?php echo $binder->borrower_phone; ?></td>
                            <td><?php echo $binder->loan_number; ?></td>
                            <td class="<?php echo  str_replace(' ','_', $statuses[$binder->status]); ?>"><span  class="<?php // echo $class  ?>"><?php echo $statuses[$binder->status]; ?></span></td>
                            <!--<td><?php // echo "Pending"; ?></td>-->
                            <!--<td><?php // echo  mdate('%M %d, %Y',strtotime($binder->closing_date));  ?></td>-->
                            <td><a href="javascript:void(0);" class="view_binder" rel="<?php echo $binder->id; ?>" data-toggle="modal" data-target="#myModal"><i class="fa fa-eye"></i></a></td>
                        </tr>

                    <?php }
                } else { ?>
                    <tr><td colspan="6">No records were found in our database.</td></tr>
<?php } ?>
            </tbody></table>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($binders->exists()) ? $binders->paged->current_row + 1 : 0; ?> to <?php echo $binders->paged->current_row + $binders->paged->items_on_page; ?> of <?php echo $binders->paged->total_rows; ?> (<?php echo $binders->paged->total_pages; ?>  Pages)</div>
        </div>
    </div><!-- /.box-body -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $(".view_binder").click(function() {
            var viewID = $(this).attr("rel");
            if (viewID) {
                $.ajax({
                    method: "POST",
                    url: "<?php echo site_url('/users/account/view-binder'); ?>",
                    data: {viewID: viewID, "ajaxCall": true},
                    success: function(data) {
                        $(".modal-title").html('View Binder Request');
                        $(".modal-body").html(data);
                    }
                });
            }
        });
    });
</script>