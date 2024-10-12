
    jQuery(document).ready(function(){
        jQuery('.cert-loader').hide()
        jQuery('#div-cert-error').html('').hide(); 
        jQuery('#cert-download').click(function(e) {
            e.preventDefault();
            //console.log("here");
            jQuery('#cert-download').attr('disabled', true);
            jQuery('#cert-download').html('Downloading....')
            let cert_type = jQuery(this).attr('data-val-type')
            converHTMLFileToPDF(cert_type);
        })

        jQuery('.modal-close').click(function(e) {
            e.preventDefault();
            jQuery('.modal').modal('toggle');
        })
       
        jQuery('#cert_send_mail').click(function(e) {
            e.preventDefault();                                                 
        })
        jQuery('#cert_add').click(function(e) {
            

        })

        jQuery('.cert_batch_view').click(function(e) {
            e.preventDefault();
           let itemId = jQuery(this).attr('data-val');
           jQuery('#batch_id').val(itemId);
           let endPointUrl = window.location.origin+"/cert/wp-json/cert/v1/fetch_certficates/"+itemId;
             jQuery.ajax({
                url:endPointUrl,
                type: "get",
                cache: false,
                processData:false,
                contentType: false,
                dataType: 'json',
                success: function(result){
            
                    if(result.data){
                        let body = "";
                        jQuery('#cert_batch_detail_body').empty()
                        jQuery.each(result.data,function(k,v){
                           body +=  "<tr><td>" +v.owner_name+"</td><td>"+ v.owner_email+"</td><td>"+v.certificate_type+ "</td> <td>"+v.issue_date+"</td><td><a href='#' class='btn btn-danger' onclick='openEditModal("+v.id+"); return false;'>Edit</a></td></tr>"; 
                        });
                        jQuery('#cert_batch_detail_body').append(body);
                    }
                },error: function(error){
                    alert('Network Error please try gain');
                }
            });
        });

        jQuery('.cert_batch_send').click(function(e) {
            e.preventDefault();
           let itemId = jQuery(this).attr('data-val');
           let endPointUrl = window.location.origin+"/cert/wp-json/cert/v1/send_mails/"+itemId;
            jQuery.ajax({
                url:endPointUrl,
                type: "get",
                cache: false,
                processData:false,
                contentType: 'application/json',
                dataType: 'json',
                success: function(result){
                    alert("Certificate batch sent successfully");
                    location.reload();
                    //console.log("success",result);
                },error: function(error){
                    alert("Some error ocurred please try again");
                }
            });
        });

        function converHTMLFileToPDF(cert_type) {
            let mode = 'l';
            let width = 289
            let height = 200;
            if(cert_type != "Certificate of Attendance"){
                mode = 'p';
                width = 210;
                height = 289;
            }
            const { jsPDF } = window.jspdf;
            var pdfjs = document.querySelector('#cert-doc');
            var name = jQuery('#cert-doc').attr('data-val');
            
            html2canvas(pdfjs).then(canvas => {
            const contentDataURL = canvas.toDataURL('image/png',0.5)
            var pdf = new jsPDF(mode, 'mm', 'a4', true);
            pdf.addImage(contentDataURL, "JPEG", 0, 0, width, height, undefined, 'FAST');
            pdf.save(name);
            jQuery('#cert-download').attr('disabled', false);
            jQuery('#cert-download').html('Download')
            });
           
        }
       
        jQuery('#cert-save-data').click(function(e){
            e.preventDefault();
            jQuery('#cert-save-data').attr('disabled',true)
            jQuery('#div-cert-error').html('').hide(); 
            let itemId = jQuery('#cert_id').val();
            let endPointUrl = window.location.origin+"/cert/wp-json/cert/v1/certificates/"+itemId;
            jQuery('.cert-loader').show()
            let formData = new FormData()
          
            if(jQuery('#owner_name').val() != ''){
                formData.append('owner_name',jQuery('#owner_name').val());
            }
            if (jQuery('#owner_email').val() != '') {
                formData.append('owner_email',jQuery('#owner_email').val());
            }
            if (issue_date != jQuery('#issue_date').val()) {
                formData.append('issue_date',jQuery('#issue_date').val());
            }

            if(jQuery('#certificate_type').val() != ''){
                formData.append('certificate_type',jQuery('#certificate_type').val());
            }
            formData.append('id', itemId);
            formData.append('batch_id',jQuery('#batch_id').val())
            
            jQuery.ajax({
                url:endPointUrl,
                type: "post",
                cache: false,
                processData:false,
                contentType: false,
                data: formData,
                dataType: 'json',
                success: function(result){
                    jQuery('.cert-loader').hide()
                    jQuery('#cert-save-data').attr('disabled',false)
                    jQuery('#div-cert-error').html('').hide(); 
                   if(result.data){
                   
                     alert("Data saved Successfully")
                     location.reload(true);
                   }
                  
                },error: function(error){
                    jQuery('#cert-save-data').attr('disabled',false)
                    jQuery('.cert-loader').hide()
                    if(error.responseJSON.code == "rest_missing_callback_param"){
                        
                       let response = error.responseJSON.data.params;
                       let message = []
                        response.forEach((v,k) => {
                          message [k] = `The ${v} field is required`
                        });
                       let mes =  message.join('<br>');
                       jQuery('#div-cert-error').html(mes.replaceAll('_'," ")).show(); 
                    }else{
                        alert("Network Error please check your internet connection speed and try again")
                    }
                }
            });
        })     

    })

    function openEditModal(itemId){
        
        jQuery('.modal').modal('toggle');
        jQuery('#cert_id').val(itemId);
        let endPointUrl = window.location.origin+"/cert/wp-json/cert/v1/fetch_certficates/"+itemId;
        jQuery('.cert-loader').show()
            jQuery.ajax({
                url:endPointUrl,
                type: "get",
                cache: false,
                processData:false,
                contentType: false,
                dataType: 'json',
                success: function(result){
                   
                   if(result.data){
                   
                        jQuery('#owner_name').val(result.data[0].owner_name)
                        jQuery('#owner_email').val(result.data[0].owner_email)
                        jQuery('#certificate_type').val(result.data[0].certificate_type)
                        let date = new Date(result.data[0].issue_date);
                        //console.log(result.data[0].issue_date);
                        let year = date.getFullYear();
                        let day = date.getDate();
                        let month = date.getMonth() + 1;
                        day = day > 10 ? day : "0"+day;
                        month = month > 10 ? month : "0"+month;
                        
                        let datee = year+"-"+month+"-"+day
                     
                        jQuery('#issue_date').val(datee);
                   }
                   jQuery('.cert-loader').hide()
                },error: function(error){
                    jQuery('.cert-loader').hide()
                }
            });
       
    }