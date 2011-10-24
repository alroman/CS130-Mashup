<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="http://twitter.github.com/bootstrap/1.3.0/bootstrap.min.css"></link>
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
                <tr>
                    <th>1</th><th><?php echo $test1 ?></th>
                </tr>
                <tr>
                    <th>2</th>
                    <th>
                        <?php 
                        echo "<pre>";
                        var_dump($test2);
                        echo"</pre>";
                        ?>
                    </th>
                </tr>
            </tbody>
        </table>
    </div>
    </body>
</html>