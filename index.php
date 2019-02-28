<?php
if ($_GET['isAjax'] ?? false) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    require __DIR__ . '/vendor/autoload.php';

    $consumerKey = $_POST['consumerKey'];
    $consumerSecret = $_POST['consumerSecret'];
    $accessToken = $_POST['accessToken'];
    $accessTokenSecret = $_POST['accessTokenSecret'];

    $method = $_POST['method'] ?? 'get';
    $requestUrl = $_POST['requestUrl'];

    $jsonBody = $_POST['jsonBody'];

    $oauthGenerator = new \App\OAuthGenerator($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_POSTFIELDS, $jsonBody);
    curl_setopt_array($curl, [
        CURLOPT_RETURNTRANSFER => 1,
        CURLOPT_URL => $requestUrl,
        CURLOPT_HTTPHEADER => [
            $oauthGenerator->getAuthorizationHeader($requestUrl, $method),
            'Content-Type: application/json',
            'Content-Length: ' . strlen($jsonBody),
        ],
    ]);

    $result = curl_exec($curl);
    curl_close($curl);

    header('Content-Type: application/json');
    echo $result;

} else {
?>
    <!doctype html>
    <html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
        <title>Magento2 API tester</title>
    </head>
    <body>
    <h1>Magento2 API tester</h1>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <form id="api-tester">
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="consumerKey">Consumer key</label>
                <input class="form-control" name="consumerKey" placeholder="Consumer key">
            </div>
            <div class="form-group col-md-6">
                <label for="consumerSecret">Consumer secret</label>
                <input class="form-control" id="consumerSecret" name="consumerSecret" placeholder="Consumer secret">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group col-md-6">
                <label for="accessToken">Access token</label>
                <input class="form-control" id="accessToken" name="accessToken" placeholder="Access token">
            </div>
            <div class="form-group col-md-6">
                <label for="accessTokenSecret">Access token secret</label>
                <input class="form-control" id="accessTokenSecret" name="accessTokenSecret" placeholder="Access token secret">
            </div>
        </div>
        <div class="form-row">
            <div class="col-md-6">
                <div class="form-group col-md-12">
                    <label for="requestUrl">Request url</label>
                    <input type="text" class="form-control" id="requestUrl" name="requestUrl">
                </div>
                <div class="form-group col-md-12">
                    <label for="method">Method</label>
                    <select id="method" name="method" class="form-control">
                        <option value="POST">POST</option>
                        <option value="GET">GET</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group col-md-12">
                    <label for="requestUrl">Request body</label>
                    <textarea class="form-control" id="jsonBody" name="jsonBody" rows="5"></textarea>
                </div>
            </div>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
        <div>
            <h3>Response:</h3>
            <pre id="response">
            </pre>
        </div>
    </form>
    <script>
        var form = document.getElementById('api-tester');
        function processForm(e) {
            if (e.preventDefault) e.preventDefault();
            document.getElementById('response').innerHTML = 'loading...';

            var http = new XMLHttpRequest();
            var params = new FormData(form);

            var methodElement = document.getElementById("method");
            var method = methodElement.options[methodElement.selectedIndex].value;

            http.open(method, '?isAjax=1', true);

            http.onreadystatechange = function() {//Call a function when the state changes.
                try {
                    var responseBody = JSON.parse(http.responseText);
                    responseBody = JSON.stringify(responseBody, null, 2);
                } catch(e) {
                    responseBody = http.responseText;
                }

                document.getElementById('response').innerHTML = responseBody;
            };
            http.send(params);

            return false;
        }

        if (form.attachEvent) {
            form.attachEvent("submit", processForm);
        } else {
            form.addEventListener("submit", processForm);
        }
    </script>
</body>
</html>
<?php
}
