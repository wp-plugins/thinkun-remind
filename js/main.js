function emailPreviewFrame()
{
	if(sendMailValidation())
	{
		var name = $('#name').val();
		var message = $('#message').val();
		var templateId =$('#template option:selected').val();
		var customField = $('#customField').val();
		var jsonObj = "{"; 
		if(customField.length>0)
			{
				customField =  customField.split("|");		
				
				for(i=0;i<customField.length;i++)
				{
					if($('#'+customField[i]))
					{	
						var fieldValue = $('#'+customField[i]).val();
						var fieldLabel = customField[i];				
						//field[i] = fieldLabel+'|'+fieldValue;
						//jsonObj.push({fieldLabel: fieldValue});
						jsonObj += "\"" + fieldLabel + "\" : \"" + fieldValue + "\",";
					}			
				}	
				
				jsonObj = jsonObj.substring(0, jsonObj.length-1);
				
				//var obj = jsonObj.evalJSON(true);
				//alert(jsonObj); //debug
			}
		jsonObj += "}";
		
		var previewName = $('#previewName').val(name);
		var previewMessage = $('#previewMessage').val(message);
		var previewTemplateId = $('#previewTemplateId').val(templateId);
		var previewCustomField = $('#previewCustomField').val(jsonObj);
		
		document.frmPreview.submit()
		$('#preview').show();
		$('#previewIframe').show();
		$('#send').css("display","block");
	}
}

function array2String(array) {
    var output = "";
    if (array) {
        output += "[";
        for (var i in array) {
            val = array[i];
            switch (typeof val) {
                case ("object"):
                    if (val[0]) {
                        output += array2String(val) + ",";
                    } else {
                        output += object2String(val) + ",";
                    }
                    break;
                case ("string"):
                    output += "'" + escape(val) + "',";
                    break;
                default:
                    output += val + ",";
            }
        }
        output = output.substring(0, output.length-1) + "]";
    }
    return output;
}

function sendMailValidation()
{
	var templateId,name,to,subject,msg='';
	var flag=1;
	
	name = $('#name').val();	
	to = $('#email').val();	
	subject = $('#subject').val();	
	templateId = $('#template').val();	
	
	var emailReg = /^[a-zA-Z0-9._+-]+@[a-zA-Z0-9.+-]+\.[a-zA-Z]{2,4}$/;
 
	if(templateId == '')
	{
		msg += "Template name is required \n";
		flag=0;
	}
	
	if(name == '')
	{
		msg += "Name is required \n";
		flag=0;
	}
	
	if(subject == '')
	{
		msg += "Subject is required \n";
		flag=0;
	}
	
	if(to == '')
	{
		msg += "Email address is required \n";
		flag=0;
	}
	else if(!emailReg.test(to)) {
		msg += "Email address is not valid \n";
		flag=0;
	}
 
	
	if(flag == 1)
	{
		return true;
	}
	else 
	{
		alert(msg);
		return false;
	}
}

function templateDataValidation()
{
	var msg = '';
	var flag = 1;
	var templateName = $("#templateName").val();
	var renderAs = $('#renderAs:checked').val();
	var templateText = $("#templateText").val();
	
	if(templateName == '')
	{
		msg += "Template name is required \n";
		flag=0;
	}
	
	if(renderAs == undefined)
	{
		msg += "Render as is required \n";
		flag=0;
	}
	
	if(templateText == '')
	{
		msg += "Template content is required \n";
		flag=0;
	}
	
	if(flag == 1)
	{
		return true;
	}
	else 
	{
		alert(msg);
		return false;
	}
}

function templatePreview()
{
	if(templateDataValidation())
	{
		var templateText = $('#templateText').val();
		var render_as = $('input[name=renderAs]:checked').val();
		$('#previewRenderAs').val(render_as);
		$('#previewTemplateText').val(templateText);
		document.frmPreview.submit()
		$('#preview').show();
		$('#previewIframe').show();
		$('#btnSave').css("display","block");
	}
}


function deleteTemplate(tId)
{
	$result = confirm("Are you sure you want to delete this template? This action cannot be undone.");
	if($result)
	{
		return true;
	}
	else 
	{
		return false;
	}
}

function deleteField(fieldId,field)
{
	$result = confirm("Are you sure you want to delete this custom field? If this custom field is being used in a template, it will render as ["+field+"] instead of field data.");
	if($result)
	{
		return true;
		
	}
	else 
	{
		return false;
	}
}

