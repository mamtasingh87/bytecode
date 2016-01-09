<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> Auto Registered Users </h1>
    </div>
    <div class="content">
        <form id="filter_form" method="post" action="<?php echo site_url(ADMIN_PATH . '/reports/autousers/index'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="first_name" placeholder="First Name" value="<?php echo isset($params['first_name']) ? $params['first_name'] : ''; ?>"/></th>
                        <th><input type="text" name="last_name" placeholder="Last Name" value="<?php echo isset($params['last_name']) ? $params['last_name'] : ''; ?>"/></th>
                        <th><input type="text" name="email" placeholder="Email" value="<?php echo isset($params['email']) ? $params['email'] : ''; ?>"/></th>
                        <th><input type="text" name="phone" placeholder="Phone No" value="<?php echo isset($params['phone']) ? $params['phone'] : ''; ?>"/></th>
                        <th>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/reports/autousers/index/'). '?reset=true'; ?>'"><span>Clear</span></button>
                        </th>
                    </tr>
                </thead>
            </table>
        </form>
        <table class="list">
            <thead>
                <tr>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone No</th>
                    <th>Group</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($report): ?>
                    <?php foreach ($report as $value): ?>
                        <tr>
                            <td><?php echo $value->first_name; ?></td>
                            <td><?php echo $value->last_name; ?></td>
                            <td><?php echo $value->email; ?></td>
                            <td><?php echo $value->phone; ?></td>
                            <td>Front Users</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="5">No reports have been found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($report) ? $limit + 1 : 0; ?> to <?php echo $limit + count($report); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>