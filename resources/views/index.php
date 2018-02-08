<div class="panel panel-default">
    <!-- Default panel contents -->
    <div class="panel-heading">
        <div class="form pull-right">
            <form id="upload-form" action="/load-csv">
                <input type="file" id="file-input" name="fileToUpload">
                <button type="button" id="browse-button" class="btn btn-success"
                        data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing">
                    <i class="glyphicon glyphicon-folder-open"></i> Upload file
                </button>
            </form>
        </div>
        <h1>Products list</h1>
    </div>

    <!-- Table -->
    <table class="table table-hover">
        <thead>
        <tr>
            <th>Product</th>
            <th>Quantity</th>
            <th>Warehouse</th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ($products as $product) { ?>
            <tr>
                <td><?= htmlentities($product->name) ?></td>
                <td><?= number_format($product->qty, 1) ?></td>
                <td><?= htmlentities($product->warehouses) ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
</div>