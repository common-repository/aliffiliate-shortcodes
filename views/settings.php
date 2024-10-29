<?php
    if (!defined('ABSPATH')) exit;
    $page_name = 'Settings';
    include 'top.php'
?>
<form method="post" action="">
    <div class="settings">
        <table class="formtable">
            <tr>
                <td>Api Key:</td>
                <td><input type="text" name="afApiKey" id="af-api-key" value="<?php echo esc_attr($afApiKey); ?>" />
                    <span class="inputinfo">Get API key from AliExpress website</span>
                </td>
            </tr>

            <tr>
                <td>Tracking ID:</td>
                <td><input type="text" name="afTrackingId" id="af-tracking-id" value="<?php echo esc_attr($afTrackingId); ?>" />
                    <span class="inputinfo">Your Affiliate ID from AliExpress website</span>
                </td>
            </tr>
        </table>
    </div>
    
    <div class="settings">
        <table>
            <tr>
                <td></td>
                <td><input type="submit" name="save" value="Save" /></td>
            </tr>
        </table>
    </div>

</form>
<?php
    include 'bottom.php';
?>