<?php
$totalRequest = 0;
$totalPendingRequest = 0;
$totalApprovedRequest = 0;
$totalDisapprovedRequest = 0;
?>
<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> Binder Reports </h1>
    </div>
    <div class="content">

        <table class="list">
            <thead>
                <tr>
                    <th>Requested By</th>
                    <th>Total Requests</th>
                    <th>Total Pending</th>
                    <th>Total Approved</th>
                    <th>Total Disapproved</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($report): ?>
                    <?php foreach ($report as $value): ?>
                        <?php
                        $totalRequest += $value->total_requests;
                        $totalPendingRequest += $value->total_pending;
                        $totalApprovedRequest += $value->total_approved;
                        $totalDisapprovedRequest += $value->total_disapproved;
                        ?>
                        <tr>
                            <td><?php echo $value->first_name . ' ' . $value->last_name; ?></td>
                            <td><?php echo $value->total_requests; ?></td>
                            <td><?php echo $value->total_pending; ?></td>
                            <td><?php echo $value->total_approved; ?></td>
                            <td><?php echo $value->total_disapproved; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="5">No reports have been found.</td>
                    </tr>
                <?php endif; ?>
            <thead>
                <tr>
                    <th>Total</th>
                    <th><?php echo $totalRequest; ?></th>
                    <th><?php echo $totalPendingRequest; ?></th>
                    <th><?php echo $totalApprovedRequest; ?></th>
                    <th><?php echo $totalDisapprovedRequest; ?></th>
                </tr>
            </thead>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($report) ? $limit + 1 : 0; ?> to <?php echo $limit + count($report); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>