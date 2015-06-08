<div class="content">
    <div class="wrapper">
        <h1>Dashboard</h1>
        <div class="left-box-content">
            <form  class="form-box" id="randomInsert"
                   action="<?php echo Config::get('BASE_URL');?>dashboard/xhrInsert/" method="post">
                <ul class="form-style">
                    <li><label>Dashboard</label>
                        <input type="text" name="text"  id="field-input-dashboard" class="field-input-dashboard" autocomplete="off"
                               placeholder="write anything you want..." />
                    </li>
                    <li><input type="submit" value="Submit"/></li>
                </ul>
            </form>
        </div>
        <div class="right-box-content">
            <div id="listInserts" class="dashboard-results"></div>
        </div>
    </div>
</div>

