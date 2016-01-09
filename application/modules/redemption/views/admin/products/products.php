<div class="box">
    <div class="heading">
        <h1><img alt="" src="<?php echo theme_url('assets/images/category.png'); ?>"> Products </h1>

        <div class="buttons">
            <a class="button" href="<?php echo site_url(ADMIN_PATH . "/redemption/products/edit"); ?>"><span>Add Product</span></a>
            <a class="button delete" href="#"><span>Delete</span></a>
        </div>
    </div>
    <div class="content">
        <form id="filter_form" method="post" action="<?php echo site_url(ADMIN_PATH . '/redemption/products/index'); ?>">
            <table class="list">
                <thead>
                    <tr>
                        <th><input type="text" name="name" placeholder="Name" value="<?php echo isset($params['name']) ? $params['name'] : ''; ?>"/></th>
                        <th><input type="text" name="sku" placeholder="SKU" value="<?php echo isset($params['sku']) ? $params['sku'] : ''; ?>"/></th>
                        <th><input type="text" name="slug" placeholder="Slug" value="<?php echo isset($params['slug']) ? $params['slug'] : ''; ?>"/></th>
                        <th>
                            <select name="in_stock">
                                <?php foreach ($in_stock as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo isset($params['in_stock']) && $params['in_stock'] == $key ? 'selected="selected"' : ''; ?>><?php echo $val ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th><input type="text" name="quantity" placeholder="Quantity" value="<?php echo isset($params['quantity']) ? $params['quantity'] : ''; ?>"/></th>
                        <th>
                            <select name="enabled">
                                <?php foreach ($status as $key => $val) { ?>
                                    <option value="<?php echo $key; ?>" <?php echo isset($params['enabled']) && $params['enabled'] == $key ? 'selected="selected"' : ''; ?>><?php echo $val ?></option>
                                <?php } ?>
                            </select>
                        </th>
                        <th><input type="text" name="price" placeholder="Price" value="<?php echo isset($params['price']) ? $params['price'] : ''; ?>"/></th>
                        <th>
                            <button type="submit" class="button"><span>Filter</span></button>
                            <button type="button" class="button" onclick="window.location.href = '<?php echo site_url(ADMIN_PATH . '/redemption/products/index/') . '?reset=true'; ?>'"><span>Clear</span></button>
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
                    <th>Name</th>
                    <th>SKU</th>
                    <th>Slug</th>
                    <th>In Stock</th>
                    <th>Quantity</th>
                    <th>Status</th>
                    <!--<th>Price</th>-->
                    <!--<th>Sale Price</th>-->
                    <th>Order</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($products): ?>
                    <?php foreach ($products as $value): ?>
                        <tr>
                            <td class="center"><input type="checkbox" value="<?php echo $value->id ?>" name="selected[]" /></td>
                            <td><?php echo $value->name; ?></td>
                            <td><?php echo $value->sku; ?></td>
                            <td><?php echo $value->slug; ?></td>
                            <td><?php echo $in_stock[$value->in_stock]; ?></td>
                            <td><?php echo $value->in_stock == 1 ? $value->quantity : ''; ?></td>
                            <td><?php echo $status[$value->enabled]; ?></td>
                            <!--<td>$<?php // echo $value->price; ?></td>-->
                            <!--<td>$<?php // echo $value->saleprice; ?></td>-->
                            <td><?php echo $value->p_order; ?></td>
                            <td>[ <a href="<?php echo site_url(ADMIN_PATH . '/redemption/products/edit') . '/' . $value->id; ?>">Edit</a> ]</td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td class="center" colspan="10">No products have been found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        <?php echo form_close(); ?>
        <div class="pagination">
            <div class="links"><?php echo $this->pagination->create_links(); ?></div>
            <div class="results">Showing <?php echo ($products) ? $limit + 1 : 0; ?> to <?php echo $limit + count($products); ?> of <?php echo $total; ?> (<?php echo $no_pages; ?>  Pages)</div>
        </div>
    </div>
</div>
<?php js_start(); ?>
<script type="text/javascript">
    $(document).ready(function() {
        $('.delete').click(function() {
            if (confirm('Delete cannot be undone! Are you sure you want to do this?'))
            {
                $('#form').attr('action', '<?php echo site_url(ADMIN_PATH . '/redemption/products/delete'); ?>').submit()
            }
            else
            {
                return false;
            }
        });
    });
</script>
<?php js_end(); ?>
