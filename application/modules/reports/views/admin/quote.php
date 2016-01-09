<?php 
$totalRequest = 0;
$totalPendingRequest = 0;
$totalApprovedRequest = 0;
$totalDisapprovedRequest = 0;
?>
<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png');   ?>"> Quote Reports </h1>
    </div>
    <div class="content">
<!--        <div class="filter">
            <form id="filter_form" method="get" action="<?php // echo site_url(ADMIN_PATH . '/quote/quote/'); ?>">
                <div class="left">
                    <div><label>Search:</label></div>
                    <input type="text" name="filter" />
                </div>
                <div class="left filter_buttons">
                    <button type="submit" class="button"><span>Filter</span></button>
                    <button type="button" class="button" onclick="window.location.href = '<?php // echo site_url(ADMIN_PATH . '/quote/quote/'); ?>'"><span>Clear</span></button>
                </div>
            </form>
            <div class="clear"></div>
        </div>-->

<?php // echo form_open(null, 'id="form"');  ?>
        <table class="list">
            <thead>
                <tr>
                    <th>Requested By</th>
                    <th>Total Requests</th>
                    <th>Pending Requests</th>
                    <th>Approved Requests</th>
                    <th>Disapproved Requests</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($reports): ?>
                <?php foreach ($reports as $report): ?>
                <?php 
                $totalRequest += $report->total_request;
                $totalPendingRequest += $report->pending_request;
                $totalApprovedRequest += $report->approved_request;
                $totalDisapprovedRequest += $report->disapproved_request;
                ?>
                        <tr>
                            <td><?php echo $report->first_name . ' ' . $report->last_name; ?></td>
                            <td><?php echo $report->total_request; ?></td>
                            <td><?php echo $report->pending_request; ?></td>
                            <td><?php echo $report->approved_request; ?></td>
                            <td><?php echo $report->disapproved_request; ?></td>
                        </tr>
                <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="5">No request have been added.</td>
                    </tr>
                <?php endif; ?>
            <thead>
                    <tr>
                    <th>Total</th>
                    <th><?php echo $totalRequest;?></th>
                    <th><?php echo $totalPendingRequest;?></th>
                    <th><?php echo $totalApprovedRequest;?></th>
                    <th><?php echo $totalDisapprovedRequest;?></th>
                </tr>
            </thead>
            </tbody>
        </table>
<?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($reports) ? $limit + 1 : 0; ?> to <?php echo $limit + count($reports); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>

