<script>
    var baseurl = '<?php echo base_url(); ?>';
</script>
<div id="page-wrapper">
    <div class="main-page">
        <h1 class="page-title">My Profile</h1>
        <div class="row">
            <div class="col-md-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <form action="<?php echo base_url(); ?>ChangeThemeColor/insert_theme_data" name="update_myprofile" id="update_myprofile"  data-parsley-validate="" enctype="multipart/form-data" method="post" accept-charset="utf-8" novalidate>
                           <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select background colour:</label>
                                    <span id="init"></span> 
                                <select name="background_color">
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#f1f1f1'){?> selected <?php }?> value="#f1f1f1">#f1f1f1</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['background_color']) && $information[0]['background_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select font colour:</label>
                                    <span id="init"></span> 
                                <select name="body_font_color">
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#333'){?> selected <?php }?> value="#333">#333</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['body_font_color']) && $information[0]['body_font_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                           <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select panel background colour:</label>
                                    <span id="init"></span> 
                                <select name="panel_color">
                                    <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#fff'){?> selected <?php }?> value="#fff">#fff</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['panel_color']) && $information[0]['panel_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select title colour:</label>
                                    <span id="init"></span> 
                                <select name="title_color">
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#1766a6'){?> selected <?php }?>  value="#1766a6">#1766a6</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#fff'){?> selected <?php }?> value="#fff">#fff</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['title_color']) && $information[0]['title_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>
                            
                            <div class="clearfix"> </div>
                             
                             <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select header colour:</label>
                                    <span id="init"></span> 
                                <select name="header_color">
                                    <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#fff'){?> selected <?php }?> value="#fff">#fff</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#1766a6'){?> selected <?php }?>  value="#1766a6">#1766a6</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['header_color']) && $information[0]['header_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group" >
                                    <label for="recipient-name" class="control-label">Select Footer colour:</label>
                                    <span id="init"></span> 
                                <select name="footer_color">
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#fff'){?>     selected <?php }?> value="#fff">#fff</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#1766a6'){?> selected <?php }?>  value="#1766a6">#1766a6</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#ac725e'){?> selected <?php }?> value="#ac725e">#ac725e</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#d06b64'){?> selected <?php }?> value="#d06b64">#d06b64</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#f83a22'){?> selected <?php }?> value="#f83a22">#f83a22</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#fa573c'){?> selected <?php }?> value="#fa573c">#fa573c</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#ff7537'){?> selected <?php }?> value="#ff7537">#ff7537</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#ffad46'){?> selected <?php }?> value="#ffad46">#ffad46</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#42d692'){?> selected <?php }?> value="#42d692">#42d692</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#16a765'){?> selected <?php }?> value="#16a765">#16a765</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#7bd148'){?> selected <?php }?> value="#7bd148">#7bd148</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#b3dc6c'){?> selected <?php }?> value="#b3dc6c">#b3dc6c</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#fbe983'){?> selected <?php }?> value="#fbe983">#fbe983</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#fad165'){?> selected <?php }?> value="#fad165">#fad165</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#92e1c0'){?> selected <?php }?> value="#92e1c0">#92e1c0</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#9fe1e7'){?> selected <?php }?> value="#9fe1e7">#9fe1e7</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#9fc6e7'){?> selected <?php }?> value="#9fc6e7">#9fc6e7</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#4986e7'){?> selected <?php }?> value="#4986e7">#4986e7</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#9a9cff'){?> selected <?php }?> value="#9a9cff">#9a9cff</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#b99aff'){?> selected <?php }?> value="#b99aff">#b99aff</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#c2c2c2'){?> selected <?php }?> value="#c2c2c2">#c2c2c2</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#cabdbf'){?> selected <?php }?> value="#cabdbf">#cabdbf</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#cca6ac'){?> selected <?php }?> value="#cca6ac">#cca6ac</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#f691b2'){?> selected <?php }?> value="#f691b2">#f691b2</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#cd74e6'){?> selected <?php }?> value="#cd74e6">#cd74e6</option>
                                        <option <?php if(isset($information[0]['footer_color']) && $information[0]['footer_color'] == '#a47ae2'){?> selected <?php }?> value="#a47ae2">#a47ae2</option>
                                </select>
                                </div>
                            </div>
                            <div class="clearfix"> </div>
                            <div class="modal-footer">
                                <input type="hidden" id="form_secret" name="form_secret"  value="<?php echo createFormToken(); ?>">
                                 <input type="hidden" name="user_theme_id" value="<?= !empty($information[0]['user_theme_id']) ? $information[0]['user_theme_id'] : '' ?>">
                                <input type="button" onclick="reset_theme_color();" value="Reset" id="Reset" name="Reset" class="btn btn-danger">

                                <input type="submit" value="submit" id="submit_btn" name="submit_btn" class="btn btn-default">
                                
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <div class="clearfix"> </div>
        </div>
    </div>
</div>
