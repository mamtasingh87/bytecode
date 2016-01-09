<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Categories </h1>
        <div class="buttons">
            <select id="status">
                <option value="">Select Status</option>
                <?php foreach ($categoryStatus as $key => $value) { ?>
                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                <?php } ?>
            </select>
            <a class="button changestatus" href="#"><span>Change Status</span></a>
            <a class="button delete" href="#"><span>Delete</span></a>
            <a class="button" href="<?php echo site_url(ADMIN_PATH . "/redemption/categories/edit"); ?>"><span>Add Category</span></a>
            
        </div>
    </div>
    <div class="content">
        <form id="filter_form" method="POST" action="<?php echo site_url(ADMIN_PATH . '/redemption/categories/'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="id" placeholder="Id" value="<?php echo isset($params['id']) ? $params['id'] : ''; ?>"/></th>
                        <th><input type="text" name="name" placeholder="Category Name" value="<?php echo isset($params['name']) ? $params['name'] : ''; ?>"/></th>
                        <th><input type="text" name="slug" placeholder="Slug" value="<?php echo isset($params['slug']) ? $params['slug'] : ''; ?>"/></th>
                        <th>
                            <select name="enabled">
                                <option value=''>Select Status</option>
                                <?php foreach ($categoryStatus as $key => $value) { ?>
                                    <option value="<?php echo $key; ?>"><?php echo $value; ?></option>
                                <?php } ?>
                            </select> 
                        </th>
                        <th>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/redemption/categories/') . '?reset=true'; ?>'"><span>Clear</span></button>
                        </th>
                    </tr>
                </thead>
            </table>
        </form>

        <?php echo form_open(null, 'id="form"'); ?>
        <table class="list">
            <thead>
                <tr>
                    <th width="1" class="center"><input type="checkbox" onClick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></th>
                    <th class="right"><a rel="id" class="sortable" href="#">Id</a></th>
                    <th class="right"><a rel="name" class="sortable" href="#">Name</a></th>
                    <th><a rel="slug" class="sortable" href="#">Slug</a></th>
                    <th><a rel="enabled" class="sortable" href="#">Enabled</a></th>
                    <th class="right">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($categoryData): ?>
                    <?php foreach ($categoryData as $cat): ?>
                        <tr>
                            <td class="center"><input type="checkbox" value="<?php echo $cat->id ?>" name="selected[]" /></td>
                            <td class="right"><?php echo $cat->id; ?></td>
                            <td class="right"><?php echo $cat->name; ?></td>
                            <td class="right"><?php echo $cat->slug; ?></td>
                            <td class="right"><?php echo ($cat->enabled == 1) ? "Yes" : "No"; ?></td>
                            <td class="right"> [ <a href="<?php echo site_url(ADMIN_PATH . '/redemption/categories/edit/' . $cat->id) ?>">Edit</a> ]</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="6">No categories have been added.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($categoryData) ? $limit + 1 : 0; ?> to <?php echo $limit + count($categoryData); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
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
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/redemption/categories/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=desc";
            }
            else
            {
                window.location.href = "<?php echo site_url(ADMIN_PATH . '/redemption/categories/') . '?'; ?>&sort=" + sort.attr('rel') + "&order=asc";
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
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/redemption/categories/delete'); ?>').submit()
            }
            else
            {
                return false;
            }
        });

        $('.changestatus').click(function() {
            if (confirm('Are you sure you want change the status?'))
            {
                if ($('#status').val() != 0) {
                    $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/redemption/categories/changestatus/') . '?'; ?>' + 'status=' + $('#status').val()).submit();
                }
            }
            else
            {
                return false;
            }
        });

    });
</script>
<?php js_end(); ?>