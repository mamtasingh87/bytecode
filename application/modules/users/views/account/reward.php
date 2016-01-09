<h2 class="sub-inner-head">Reward Log</h2>

<div class="box">
    <div class="box-header">

        <div class="box-tools">
        </div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <table class="table table-hover">
            <tbody><tr>
                    <th>Credited On</th>
                    <th>Points Gained</th>
                    <th>Gained From</th>
                </tr>
                <?php if ($reward_log->id) {
                    foreach ($reward_log as $reward) {
                        ?>
                        <tr>
                            <td><?php echo date(DATE_FORMAT, strtotime($reward->points_credit_on)); ?></td>
                            <td><?php echo $reward->points; ?></td>
                            <td>
                                <?php
                                if ($reward->type == 1) {
                                    echo 'Referral';
                                } else if ($reward->type == 2) {
                                    echo 'Trivia';
                                } else {
                                    echo 'Program Enrollment';
                                }
                                ?>
                            </td>
                        </tr>
                    <?php }
                } else { ?>
                    <tr><td colspan="6">No records were found in our database.</td></tr>
<?php } ?>
            </tbody></table>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($reward_log->exists()) ? $reward_log->paged->current_row + 1 : 0; ?> to <?php echo $reward_log->paged->current_row + $reward_log->paged->items_on_page; ?> of <?php echo $reward_log->paged->total_rows; ?> (<?php echo $reward_log->paged->total_pages; ?>  Pages)</div>
        </div>
    </div><!-- /.box-body -->
</div>