<?php
$params=$this->input->get();
?>
<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> Invitations </h1>

    </div>
    <div class="content">
        <div class="filter">
            <form id="filter_form" method="get" action="<?php echo site_url(ADMIN_PATH . '/trivia/invitationlog/');?>">
                <div class="left">
                    <div><label>Search:</label></div>
                    <input type="text" name="filter" value="<?php echo isset($params['filter'])?$params['filter']:'';?>"/>
                </div>
                <div class="left filter_buttons">
                    <button type="submit" class="button"><span>Filter</span></button>
                    <button type="button" class="button" onclick="window.location.href ='<?php echo site_url(ADMIN_PATH . '/trivia/invitationlog/');?>' "><span>Clear</span></button>
                </div>
            </form>
            <div class="clear"></div>
        </div>

        <table class="list">
            <thead>
                <tr>
                    <th rel="email_id" class="left sortable" href="#">Email</th>
                    <th rel="first_name" class="left sortable" href="#">Requested By</th>
                    <th rel="email_times" class="left sortable" href="#">Request Sent</th>
                    <th rel="points" class="left sortable" href="#">Points</th>
                    <th class="left">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($invitaitons): ?>
                    <?php foreach ($invitaitons as $invites): ?>
                        <tr>
                            <td class="left"><?php echo $invites->email_id; ?></td>
                            <td class="left"><?php echo $invites->first_name . ' ' . $invites->last_name; ?></td>
                            <td class="left"><?php echo $invites->email_times; ?></td>
                            <td class="left">
                                
                                 <?php
                                echo ($invites->points && $invites->status==2)?$invites->points:0.00;
                                ?>
                                
                                
                               </td>
                            <td class="left"><?php echo $status[$invites->activated]; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="7">No Invitations have been added.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($invitaitons) ? $limit + 1 : 0; ?> to <?php echo $limit + count($invitaitons); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
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
    window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/invitation_log/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
    }
    else
    {
    window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/invitation_log/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=asc";
    }

    return false;
    });
<?php if ($sort = $this->input->get('sort')): ?>
        $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
<?php else: ?>
        $('a.sortable[rel="id"]').addClass('asc');
<?php endif; ?>
</script>
<?php js_end(); ?>