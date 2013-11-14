<!DOCTYPE HTML>
<html lang="en-US">
<head>
    <meta charset="UTF-8">
    <title>Order Create Example</title>
    <link rel="stylesheet" href="layout/css/bootstrap.min.css">
    <link rel="stylesheet" href="layout/css/style.css">
</head>
<body>
<div class="container">
    <div class="page-header">
        <h1>Order create Example</h1>
    </div>
<?php
    $result = OpenPayU_OAuth::accessTokenByClientCredentials();
?>
<form method="GET" action="<?php echo OpenPayU_Configuration::getAuthUrl(); ?>">
    <fieldset>
        <legend>Process with user authentication</legend>
        <p>During this process, you will be asked to login before moving on to the summary.</p>
        <input type="hidden" name="redirect_uri" value="<?php echo $myUrl . "/BeforeSummaryPage.php";?>">
        <input type="hidden" name="response_type" value="code">
        <input type="hidden" name="client_id" value="<?php echo OpenPayU_Configuration::getClientId(); ?>">
        <p><input type="submit" class="btn btn-primary" value="Next step (user authorization) >"></p>
    </fieldset>
</form>

<form method="GET" action="<?php echo OpenPayu_Configuration::getSummaryUrl();?>">
    <fieldset>
        <legend>Process without user authentication, redirect to summary</legend>
        <p>During this process, you will be taken to a summary</p>
        <input type="hidden" name="sessionId" value="41U1U0f7b032b0e3c037a">
        <input type="hidden" name="oauth_token" value="<?php echo $result->getAccessToken();?>">
        <input type="hidden" name="order_id" value="1">
        <input type="hidden" name="orderId" value="2">
        <input type="hidden" name="customer_id" value="3">
         <input type="hidden" name="customerId" value="4">
        <p>
            <label for="showLoginDialogSelect">Show login dialog:</label>
            <select name="showLoginDialog" id="showLoginDialogSelect">
                <option value="False">No</option>
                <option value="True">Yes</option>
            </select>
            <input type="submit" class="btn btn-primary" value="Next step (summary page) >">
        </p>
    </fieldset>
</form>
</div>
</body>
</html>