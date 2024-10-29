<?php
    if (!defined('ABSPATH')) exit;
    $page_name = 'Create shortcodes';
    include 'top.php'
?>
<script type="text/javascript">
    function ClipBoard()
    {
        window.prompt("Copy to clipboard: Ctrl+C, Enter", jQuery('.result').text().trim());
        //holdtext.innerText = $('.result').text();
        //Copied = holdtext.createTextRange();
        //Copied.execCommand("Copy");
    }

jQuery(document).ready(function(){
    jQuery("#formcreate").validate({
        onkeyup: false,
        rules: {
            categoryId: {
                required: {
                    depends: function () {
                        return jQuery("#keywords").val() === '';
                    }
                }
            },
            keywords: {
                required: {
                    depends: function () {
                        return jQuery(".categoryId").val() === '';
                    }
                }
            },
            numberToImport: {
                number: true
            }
        },
        errorPlacement: function (error, element) {
            var $element = jQuery(element);
            if ($element.is('input')) {
                $element.val('');
            }
        }

    });

     jQuery(".categoryId").on('change', function () {
            jQuery("#keywords").removeClass('error');
        });
        jQuery("#keywords").on('change', function () {
            jQuery(".categoryId").removeClass('error');
        });

        jQuery('input.number')
            .on('keydown', onlyNumbers)
            .on('cut copy paste', function (e) { e.preventDefault(); });
})
    
    function validateform()
    {
        if (jQuery("#formcreate").valid()) {
            jQuery("#formcreate").submit(); }    
    }

    function onlyNumbers(e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
                // Allow: Ctrl+A
                (e.keyCode === 65 && e.ctrlKey === true) ||
                // Allow: home, end, left, right
                (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
            return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
    }
</script>
<form method="post" action="" id="formcreate">
    <div class="settings">
        <table>
            <tr>
                <td>Category</td>
                <td>
                    <select id="category-id" name="categoryId" class="dropdown categoryId">
                        <option value="">All categories </option>
                        <option value="3">Apparel & Accessories 	                       </option>
                        <option value="34">Automobiles & Motorcycles 	                   </option>
                        <option value="1501">Baby Products 	                               </option>
                        <option value="66">Beauty & Health 	                               </option>
                        <option value="7">Computer & Networking 	                       </option>
                        <option value="13">Construction & Real Estate 	                   </option>
                        <option value="44">Consumer Electronics 	                       </option>
                        <option value="100008578">Customized Products 	                   </option>
                        <option value="5">Electrical Equipment & Supplies 	               </option>
                        <option value="502">Electronic Components & Supplies 	           </option>
                        <option value="2">Food 	                                           </option>
                        <option value="1503">Furniture 	                                   </option>
                        <option value="200003655">Hair & Accessories 	                   </option>
                        <option value="42">Hardware 	                                   </option>
                        <option value="15">Home & Garden 	                               </option>
                        <option value="6">Home Appliances 	                               </option>
                        <option value="200003590">Industry & Business 	                   </option>
                        <option value="36">Jewelry & Watch 	                               </option>
                        <option value="39">Lights & Lighting 	                           </option>
                        <option value="1524">Luggage & Bags 	                           </option>
                        <option value="21">Office & School Supplies 	                   </option>
                        <option value="509">Phones & Telecommunications 	               </option>
                        <option value="30">Security & Protection 	                       </option>
                        <option value="322">Shoes 	                                       </option>
                        <option value="200001075">Special Category 	                       </option>
                        <option value="18">Sports & Entertainment 	                       </option>
                        <option value="1420">Tools 	                                       </option>
                        <option value="26">Toys & Hobbies 	                               </option>
                        <option value="1511">Watches 	                                   </option>
                    </select>
                    <span class="inputinfo">Select AliExpress category</span>
                </td>
            </tr>
            <tr>
                <td>Keyword(s)</td>
                <td>
                    <input type="text" name="keywords" id="keywords"></select>
                    <span class="inputinfo">Filter products by keyword. ***Important - You must enter keyword if you search in all categories</span>
                </td>
            </tr>
            <tr>
                <td>Number of products in shortcode</td>
                <td>
                    <input type="text" name="numberToImport" id="numberToImport" class="small number">
                    <span class="inputinfo">Number of products to show with shortcode</span>
                </td>
            </tr>
        </table>
    </div>
    <div class="settings">
        <table>
            <tr>
                <td></td>
                <td><input type="button" onclick="validateform()" name="save" value="Generate" id="save" /></td>
            </tr>
        </table>
    </div>
    <?php if (!empty($short)) {?>
    <div class="settings">
        <h3>Generated shortcode to use in your posts</h3>
        <div class="result">
            <?php echo $short; ?>

        </div>
        <textarea id="holdtext" style="display:none;">
        </textarea>
        <a href="#" onclick="ClipBoard();">Copy to Clipboard</a>
    </div>
    <?php }?>
</form>
<?php
    include 'bottom.php';
?>