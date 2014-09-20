<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>&nbsp;&nbsp;Filter&nbsp;</label>
    <select inputtype="_default" id="criteria" name="criteria" style="width: 120px">
        <option value="any">- Select -</option>
        <?php
        $criterias = $_GET['criterias'];
        for ($i = 0; $i < count($criterias); $i++) {
            if (isset($_GET['criteria']) && urldecode(str_replace(" ", "_", $_GET['criteria'])) == str_replace(" ", "_", $criterias[$i])) {
                echo "<option selected value=\"" . urlencode(str_replace(" ", "_", $criterias[$i])) . "\">" . ucwords($criterias[$i]) . "</option>";
            } else {
                echo "<option value=\"" . urlencode(str_replace(" ", "_", $criterias[$i])) . "\">" . ucwords($criterias[$i]) . "</option>";
            }
        }
        ?>
    </select>
    <label>&nbsp;&nbsp;Value&nbsp;</label>
    <input inputtype="textbox" class="textbox w120" id="value" name="value" value="<?php if(isset($_GET['value'])) {echo $_GET['value'];}?>"/>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>
