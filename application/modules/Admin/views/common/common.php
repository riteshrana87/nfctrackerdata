<script type="text/javascript">
    $(document).ready(function () {
        
        //serch by enter
         $('#searchtext').keyup(function(event) 
         {
                if (event.keyCode == 13) {
                        data_search('changesearch');
                }
        
        });
    });
    function data_search(allflag)
    {

        <?php
        $this->method =!empty($this->method)?$this->method:'index';
         ?>
        var uri_segment = $("#uri_segment").val();

        var urisegment = $("#uri_segment").val();
        if(uri_segment == 0)
        {
            var urisegment = '1'+'/'+uri_segment;
            var uri = urisegment.split('/');
            var uri_segment = uri[1];
            var urisegment = uri_segment;

        }
        else
        {
             var uri = uri_segment.split('/');

             var uri_segment = uri[1];
        }
        /* Start Added By niral*/
        var request_url = '';
       
        if (uri_segment == 0)
        {
            request_url = '<?php echo $this->config->item('admin_base_url') . '/' . $this->viewname.'/'.$this->method ?>/' + urisegment;
        } else
        {
            request_url = '<?php echo $this->config->item('admin_base_url') . '/' . $this->viewname.'/'.$this->method  ?>/' + urisegment;
        }
        /* End Added By niral*/


        $.ajax({
            type: "POST",
            url: request_url,
            data: {
                result_type: 'ajax', perpage: $("#perpage").val(), searchtext: $("#searchtext").val(), sortfield: $("#sortfield").val(), sortby: $("#sortby").val(), professional_name: $("#professional_name").val(), search_date: $("#search_date").val(), search_time: $("#search_time").val(), allflag: allflag
            },
            success: function (html) {

                $("#common_div").html(html);
            }
        });
        return false;
    }
    //Search data
    function data_search1(allflag)
    {
        var uri_segment = $("#uri_segment").val();
        $.ajax({
            type: "POST",
            url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+uri_segment,
            data: {
            result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:allflag
        },
        beforeSend: function() {
                    $('#common_div').block({ message: 'Loading...' }); 
                  },
            success: function(html){
                $("#common_div").html(html);
                $('#common_div').unblock(); 
            }
        });
        return false;
    }
    function reset_data()
    {
        $("#searchtext").val("");
         $('.chosen-select').val('').trigger('chosen:updated');
         $("#searchForm input").val("");
        apply_sorting('','');
        data_search('all');
    }

    function reset_data_list(data)
    {
        $("#searchtext").val(data);
        apply_sorting('','');
        data_search('all');
    }

    function changepages()
    {
        data_search(''); 
    }
    
    function apply_sorting(sortfilter,sorttype)
    {
        $("#sortfield").val(sortfilter);
        $("#sortby").val(sorttype);
        data_search('changesorting');
    }

    //pagination
    $('body').on('click', '#common_tb ul.pagination a.ajax_paging', function (e) {

        $.ajax({
            type: "POST",
            url: $(this).attr('href'),
            data: {
                result_type: 'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(), 
				yp_name : $('#yp_name').val(), admin_report_start_date : $('#admin_report_start_date >input').val(), admin_report_end_date: $('#admin_report_end_date > input').val(), reportType: $('#reportType').val(),
                communication_type : $('#communication_type').val(),created_by : $('#created_by').val(),medical_number : $('#medical_number').val(),date_received : $('#date_received > input').val(),staff : $('#staff').val(), professional_name: $("#professional_name").val(), search_date: $("#search_date").val(), search_time: $("#search_time").val()
            },
            beforeSend: function () {
                $('#common_div').block({message: 'Loading...'});
            },
            success: function (html) {
                $("#common_div").html(html);
                $.unblockUI();
            }
        });
        return false;

    });
    //check box for delete all
    $('body').on('click','#selecctall',function(e){
     if(this.checked) { // check select status
         $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"              
            });
        }else{
            $('.checkbox1').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"                      
            });        
        }
    });

    //check box for delete all
    $('body').on('click','#selecctall_user',function(e){
        if(this.checked) { // check select status
            $('.checkbox_user').each(function() { //loop through each checkbox
                this.checked = true;  //select all checkboxes with class "checkbox1"
            });
        }else{
            $('.checkbox_user').each(function() { //loop through each checkbox
                this.checked = false; //deselect all checkboxes with class "checkbox1"
            });
        }
    });

    function deletepopup(id,name)
    {      
            if(id == '0')
            {
                var boxes = $('input[name="check[]"]:checked');
                if(boxes.length == '0')
                  {
                        $.alert({
                            title: 'Alert!',
                            //backgroundDismiss: false,
                            content: "<strong> Please select record(s) to delete.<strong>",
                            confirm: function(){
                            }
                        }); 
                        return false;
                        
                  }
            }
            if(id == '0')
            {
                var msg = 'Are you sure want to delete Record(s)';
            }
            else
            {
                var msg = 'Are you sure want to delete '+name+'?';
            } 
            $.confirm({
                    title: 'Confirm!',
                    content: "<strong> "+msg+" "+"<strong>",
                    confirm: function(){
                        delete_all_multipal('delete',id);
                    },
                    cancel: function(){
                        
                    }
                }); 
    } 
    //Delete multiple
    function delete_all_multipal(val,id)
    {
          var boxes = $('input[name="check[]"]:checked');
          
          var myarray = new Array;
            var i=0;

            if(id != '0')
            {
                var single_remove_id = id;
            }
            else
            {
                var boxes = $('input[name="check[]"]:checked');
                $(boxes).each(function(){
                      myarray[i]=this.value;
                      i++;
                });
            }

          var url = "<?php echo $this->config->item('admin_base_url').$this->viewname.'/ajax_delete_all';?>";
          
          
          $.ajax({
          
          type: "POST",
          url: url,
          //dataType: 'json',
          data: {'myarray':myarray,'single_remove_id':id},
          success: function(data){
            //alert(data);
            $.ajax({
              type: "POST",
              url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
              data: {
              result_type:'ajax',perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
            },
            beforeSend: function() {
                  $('#common_div').block({ message: 'Loading...' }); 
                  },
              success: function(html){
                $("#common_div").html(html);
                $('#common_div').unblock(); 
              }
            });
            return false;
          }
        });
    }
    function statuspopup(id,status,name)
    {
            if(status == 1)
            {
                var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
                if(name !=''){val ='publish';}else{val ='unpublish';};
            }
            else
            {
                var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
                if(name !=''){val ='unpublish';}else{val ='publish';};
            }
            if(id == '0')
            {
                var boxes = $('input[name="check[]"]:checked');
                if(boxes.length == '0')
                  {
                        $.alert({
                            title: 'Alert!',
                            //backgroundDismiss: false,
                            content: "<strong> Please select record(s) to "+val+".<strong>",
                            confirm: function(){
                            }
                        });
                        return false;

                  }
            }
            if(id == '0')
            {
                var msg = 'Are you sure want to '+val+' Record(s)';
            }
            else
            {
                var msg = 'Are you sure want to '+val+' '+name+'?';
            }
            $.confirm({
                    title: 'Confirm!',
                    content: "<strong> "+msg+" "+"<strong>",
                    confirm: function(){
                        status_change_ajax(id,status);
                    },
                    cancel: function(){

                    }
                });
    }
    //Change status
    function status_change_ajax(id,status)
    {

        if(status == 1)
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            val ='unpublish';
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            val ='publish';
        }
        if(id != '0')
        {
            var id = id;
        }
        else
        {
            var boxes = $('input[name="check[]"]:checked');
            var myarray = new Array;
            var i=0;
            $(boxes).each(function(){
            myarray[i]=this.value;
            i++;
            });

        }
         $.ajax({
                type: "post",
                data: {'id':id,'status' : status,'myarray':myarray },
                url: url,
                 success: function(data){
            $.ajax({
              type: "POST",
              url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
              data: {
              result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
            },
            beforeSend: function() {
                  $('#common_div').block({ message: 'Loading...' });
                  },
              success: function(html){
                $("#common_div").html(html);
                $('#common_div').unblock();
              }
            });
            return false;
          }
            });
    }


    function activatpopup(id,status,name)
    {
        //alert(status);
        if(status == 'active')
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            if(name !=''){val ='publish';}else{val ='unpublish';};
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            if(name !=''){val ='unpublish';}else{val ='publish';};
        }
        if(id == '0')
        {
            var boxes = $('input[name="check[]"]:checked');
            if(boxes.length == '0')
            {
                $.alert({
                    title: 'Alert!',
                    //backgroundDismiss: false,
                    content: "<strong> Please select record(s) to "+val+".<strong>",
                    confirm: function(){
                    }
                });
                return false;

            }
        }
        if(id == '0')
        {
            var msg = 'Are you sure want to '+val+' Record(s)';
        }
        else
        {
            var msg = 'Are you sure want to '+val+' '+name+'?';
        }
        $.confirm({
            title: 'Confirm!',
            content: "<strong> "+msg+" "+"<strong>",
            confirm: function(){
                activat_change_ajax(id,status);
            },
            cancel: function(){

            }
        });
    }
    //Change status
    function activat_change_ajax(id,status)
    {

        if(status == 'active')
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/publish_record';?>";
            val ='unpublish';
        }
        else
        {
            var url =  "<?php echo $this->config->item('admin_base_url').$this->viewname.'/unpublish_record';?>";
            val ='publish';
        }
        if(id != '0')
        {
            var id = id;
        }
        else
        {
            var boxes = $('input[name="check[]"]:checked');
            var myarray = new Array;
            var i=0;
            $(boxes).each(function(){
                myarray[i]=this.value;
                i++;
            });

        }
        $.ajax({
            type: "post",
            data: {'id':id,'activated' : status,'myarray':myarray },
            url: url,
            success: function(data){
                $.ajax({
                    type: "POST",
                    url: "<?php echo $this->config->item('admin_base_url').$this->viewname?>/index/"+data,
                    data: {
                        result_type:'ajax',searchreport:$("#searchreport").val(),perpage:$("#perpage").val(),searchtext:$("#searchtext").val(),sortfield:$("#sortfield").val(),sortby:$("#sortby").val(),allflag:''
                    },
                    beforeSend: function() {
                        $('#common_div').block({ message: 'Loading...' });
                    },
                    success: function(html){
                        $("#common_div").html(html);
                        $('#common_div').unblock();
                    }
                });
                return false;
            }
        });
    }
</script>
