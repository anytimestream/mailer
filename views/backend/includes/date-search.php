<form class="normosa-ui-form" id="search_form" method="get" action="">
    <label>From&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="from" name="from" value="<?php if(isset($_GET['from'])) {echo $_GET['from'];} else {echo date('d/m/Y');} ?>"/>
    <label>&nbsp;&nbsp;To&nbsp;</label>
    <input inputtype="date" class="textbox w80" numberOfMonths="1" id="to" name="to" value="<?php if(isset($_GET['to'])) {echo $_GET['to'];} else {echo date('d/m/Y');} ?>"/>
    <label>&nbsp;&nbsp;Criteria&nbsp;</label>
    <select inputtype="_default" id="criteria" name="criteria" style="width: 120px">
        <option value="any">Any</option>
        <?php
        $criterias = $_GET['criterias'];
        for ($i = 0; $i < count($criterias); $i++) {
            if (isset($_GET['criteria']) && urldecode($_GET['criteria']) == $criterias[$i]) {
                echo "<option selected value=\"" . urlencode($criterias[$i]) . "\">" . ucwords($criterias[$i]) . "</option>";
            } else {
                echo "<option value=\"" . urlencode($criterias[$i]) . "\">" . ucwords($criterias[$i]) . "</option>";
            }
        }
        ?>
    </select>
    <label>&nbsp;&nbsp;Value&nbsp;</label>
    <input inputtype="textbox" class="textbox w120" id="value" name="value" value="<?php if(isset($_GET['value'])) {echo $_GET['value'];}?>"/>
    <button class="btn" style="border:0; font-size: 12px;float: none">Search</button>
</form>
