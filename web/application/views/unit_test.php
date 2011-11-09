<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="<?php echo base_url() ?>bootstrap.min.css"></link>
	<meta charset="utf-8">
	<title>Unit Test</title>

</head>
    <body>
    <div class="container">
        <h1>Unit Tests for E+</h1>
        <h2>Eventful tests</h2>
        <table>
            <thead>
                <tr>
                    <th>test#</th><th>result</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($units as $key => $value): ?>
                <tr>
                    <th><?php echo $key ?></th><th><?php echo $value ?></th>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    </body>
</html>