<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Reward </h1>

<!--        <div class="buttons">
            <a class="button" href="<?php // echo site_url(ADMIN_PATH . "/trivia/categories/edit"); ?>"><span>Add Category</span></a>
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>-->
    </div>
    <div class="content">

        <?php echo form_open(null, 'id="form"'); ?>
        <table class="list">
            <thead>
                <tr>
                    <th><a rel="credit_to" class="sortable" href="#">Credit To</a></th>
                    <th><a rel="points" class="sortable" href="#">Points Gained</a></th>
                    <th><a rel="type" class="sortable" href="#">Gained From</a></th>
                    <th><a rel="points_credit_on" class="sortable" href="#">Points Credited On</a></th>
                    <!--<th class="right">Action</th>-->
                </tr>
            </thead>
            <tbody>
                <?php if ($persons): ?>
                    <?php foreach($persons as $person):
                        if($person->type==1){
                            $type='Referral';
                        }else if($person->type==2){
                            $type='Trivia';
                        }else{
                            $type='Enrollment';
                        }
                        ?>
                    <tr>
                        <td><?php echo $person->fname.' '.$person->lname; ?></td>
                        <td><?php echo $person->points; ?></td>
                        <td><?php echo $type; ?></td>
                        <td><?php echo date(DATE_FORMAT,strtotime($person->points_credit_on)); ?></td>
<!--                        <td class="right"> [ <a href="<?php // echo site_url(ADMIN_PATH . '/trivia/categories/edit/' . $cat->id) ?>">Edit</a> ]</td>-->
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="4">No Reward logs stored to display.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($persons) ? $limit+1 : 0; ?> to <?php echo $limit+count($persons); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>

<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
         $('.sortable').click( function() {
            sort = $(this);

            if (sort.hasClass('asc'))
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/reward/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/reward/') . '?';  ?>&sort=" + sort.attr('rel') + "&order=asc";
            }

            return false;
        });

        <?php if ($sort = $this->input->get('sort')): ?>
            $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
        <?php else: ?>
            $('a.sortable[rel="id"]').addClass('asc');
        <?php endif; ?>
            
//        $('.delete').click(function() {
//            if (confirm('Delete cannot be undone! Are you sure you want to do this?'))
//            {
//                $('#form').attr('action', '<?php // echo site_url(ADMIN_PATH . '/trivia/categories/delete'); ?>').submit()
//            }
//            else
//            {
//                return false;
//            }
//        });

    });
</script>
<?php js_end(); ?>