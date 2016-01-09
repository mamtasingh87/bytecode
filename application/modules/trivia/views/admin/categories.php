<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Categories </h1>

        <div class="buttons">
            <a class="button" href="<?php echo site_url(ADMIN_PATH . "/trivia/categories/edit"); ?>"><span>Add Category</span></a>
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>
    </div>
    <div class="content">

        <?php echo form_open(null, 'id="form"'); ?>
        <table class="list">
            <thead>
                <tr>
                    <th width="1" class="center"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th><a rel="name" class="sortable" href="#">Category</a></th>
                    <th class="right"><a rel="id" class="sortable" href="#">#ID</a></th>
                    <th class="right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categories): ?>
                    <?php foreach($categories as $cat):?>
                    <tr>
                        <td class="center"><input type="checkbox" value="<?php echo $cat->id ?>" name="selected[]" /></td>
                        <td><?php echo $cat->name; ?></td>
                        <td class="right"><?php echo $cat->id; ?></td>
                        <td class="right"> [ <a href="<?php echo site_url(ADMIN_PATH . '/trivia/categories/edit/' . $cat->id) ?>">Edit</a> ]</td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="4">No galleries have been added.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($categories->exists()) ? $categories->paged->current_row + 1 : 0; ?> to <?php echo $categories->paged->current_row + $categories->paged->items_on_page; ?> of <?php echo $categories->paged->total_rows; ?> (<?php echo $categories->paged->total_pages; ?>  Pages)</div>
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
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/categories/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/trivia/categories/') . '?';  ?>&sort=" + sort.attr('rel') + "&order=asc";
            }

            return false;
        });

        <?php if ($sort = $this->input->get('sort')): ?>
            $('a.sortable[rel="<?php echo $sort; ?>"]').addClass('<?php echo ($this->input->get('order')) ? $this->input->get('order') : 'asc' ?>');
        <?php else: ?>
            $('a.sortable[rel="name"]').addClass('asc');
        <?php endif; ?>
            
        $('.delete').click(function() {
            if (confirm('Delete cannot be undone! Are you sure you want to do this?'))
            {
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/trivia/categories/delete'); ?>').submit()
            }
            else
            {
                return false;
            }
        });

    });
</script>
<?php js_end(); ?>