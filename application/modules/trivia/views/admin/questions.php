<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/log.png'); ?>"> Questions </h1>

        <div class="buttons">
            <a class="button" href="<?php echo site_url(ADMIN_PATH . "/trivia/questions/edit"); ?>"><span>Add Questions</span></a>
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>
    </div>
    <div class="content">

        <?php echo form_open(null, 'id="form"'); ?>
        <table class="list">
            <thead>
                <tr>
                    <th width="1" class="center"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th><a rel="qid" class="sortable" href="#">ID</a></th>
                    <th><a rel="question" class="sortable" href="#">Question</a></th>
                    <th class="right">Category</th>
                    <th class="right">Correct Answer</th>
                    <th class="right">Question Day</th>
                    <th class="right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($questions): ?>
                    <?php foreach($questions as $ques):?>
                    <tr>
                        <td class="center"><input type="checkbox" value="<?php echo $ques->qid ?>" name="selected[]" /></td>
                        <td><?php echo $ques->qid; ?></td>
                        <td><?php echo $ques->question; ?></td>
                        <td class="right"><?php echo $ques->category_name; ?></td>
                        <td class="right"><?php echo $ques->option_name; ?></td>
                        <td class="right"><?php echo  date(DATE_FORMAT,strtotime($ques->question_day)); ?></td>
                       <td class="right"> [ <a href="<?php echo site_url(ADMIN_PATH . '/trivia/questions/edit/' . $ques->qid) ?>">Edit</a> ]</td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="7">No questions have been added.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
           <div class="results">Showing <?php echo ($questions) ? $limit+1 : 0; ?> to <?php echo $limit+count($questions); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
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
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/questions/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/questions/') . '?';  ?>&sort=" + sort.attr('rel') + "&order=asc";
            }

            return false;
        });

        <?php if ($sort = $this->input->get('sort')): ?>
            $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
        <?php else: ?>
            $('a.sortable[rel="id"]').addClass('asc');
        <?php endif; ?>
            
        $('.delete').click(function() {
            if (confirm('Delete cannot be undone! Are you sure you want to do this?'))
            {
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/trivia/questions/delete'); ?>').submit()
            }
            else
            {
                return false;
            }
        });

    });
</script>
<?php js_end(); ?>