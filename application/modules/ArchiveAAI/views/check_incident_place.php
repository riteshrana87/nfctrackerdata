<div id="page-wrapper">
    <div class="main-page">       
        <div class="sticky-heading" id="sticky-heading">
            <h1 class="page-title">Accident And Incident Record <small> New Forest Care</small></h1>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="panel panel-default tile tile-profile">
                    <div class="panel-body">
                        <h2 class="modal-title" id="exampleModalLabel">Please select the type of incident </h2>
                        <div class="form-group">
                            <label><input id="educheck" type="radio" name="placeType" onchange="check_incident_type();" value="Education"> Education incident</label>
                            <br/>
                            <label><input id="carecheck" type="radio" name="placeType" onchange="check_incident_type();" value="Care" checked> Care incident</label>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <a id="careIncident" href="<?=base_url('AAI/create/1/'.$ypId); ?>" class="btn btn-default">Submit</a>
        <a id="eduIncident" style="display: none" href="<?=base_url('AAI/create/2/'.$ypId); ?>" class="btn btn-default">Submit</a>
    </div>
</div>