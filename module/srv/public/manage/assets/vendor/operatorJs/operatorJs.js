$('#start_date').datetimepicker({
	timeFormat : 'HH:mm:ss',
	dateFormat : 'yy/mm/dd'
});
$('#end_date').datetimepicker({
	timeFormat : 'HH:mm:ss',
	dateFormat : 'yy/mm/dd'
});
var count_ip_address = $("input[name=count_ip_address]:hidden").val();
$(document).ready(function() {
	if (count_ip_address < 1) {
		count_ip_address = 1;
//		number_ip_address = 1;
		addGroupIpAddress(count_ip_address);
		$("input[name=number_ip_address]:hidden").val(count_ip_address);
	}
});

function addIPAddress() {
	var number_ip_address = $("input[name=number_ip_address]:hidden").val();
	if (number_ip_address < 100) {
		count_ip_address++;
		number_ip_address++;
		addGroupIpAddress(count_ip_address);
		if (number_ip_address > 0 && number_ip_address < count_ip_address) {
			$("input[name=number_ip_address]:hidden").val(number_ip_address);
		} else {
			$("input[name=number_ip_address]:hidden").val(count_ip_address);
		}
	}
};

function addGroupIpAddress(count_ip_address) {
	var groupIpAddress = $('#ip_address');
	var dummy = '<div class="form-group" id="group_add_ip_'
			+ count_ip_address
			+ '">'
			+ '<label class="control-label col-md-3" id="label_'
			+ count_ip_address
			+ '">No.'
			+ count_ip_address
			+ '</label>'
			+ '<div class="col-md-4">'
			+ '	<div class="input-group">'
			+ '		<input type="text" class="form-control" id="ip_address_'+count_ip_address+'" name="ip_address_'
			+ count_ip_address
			+ '" value="" >'
			+ '		<span class="input-group-btn">'
			+ '			<button class="btn btn-default" id="'
			+ count_ip_address
			+ '" onclick="removeGroupIP(this.id);" type="button"><i class="fa fa-times" ></i> 削除</button>'
			+ '		</span>' + '	</div>' + '</div>' + '</div>';
	$("input[name=count_ip_address]").val(count_ip_address);
	$(dummy).appendTo(groupIpAddress);
}

function removeGroupIP(id) {
	var number_ip_address = $("input[name=number_ip_address]:hidden").val();
	$("input[name=number_ip_address]:hidden").val(
			parseInt(number_ip_address) - 1);
	$("#group_add_ip_" + id).remove();
};

function selectServiceprovider(id, name) {
	if (name == 'no_service_provider') {
		$("#no_service_provider_" + id + '_i').addClass("fa-check-circle-o");
		$("#no_service_provider_" + id + '_i').removeClass("fa-circle-o");
		$("#no_service_provider_" + id).removeClass("btn-outline");
		$("input[name=chk_input_service_provider_" + id + ']:hidden').val('0');
		//
		$("input[name=identifying_code_" + id + ']').attr("disabled", true);
		$("input[name=identifying_code_" + id + ']').val('');
		//
		$("#service_provider_" + id + '_i').removeClass("fa-check-circle-o");
		$("#service_provider_" + id + '_i').addClass("fa-circle-o");
		$("#service_provider_" + id).addClass("btn-outline");
		$("#form_group_indentifying_code_" + id).removeClass("has-error");
		$("#error_message_indentifying_code_" + id).remove();
	} else {
		$("#service_provider_" + id + '_i').addClass("fa-check-circle-o");
		$("#service_provider_" + id + '_i').removeClass("fa-circle-o");
		$("#service_provider_" + id).removeClass("btn-outline");
		$("input[name=chk_input_service_provider_" + id + ']:hidden').val('1');
		//
		$("input[name=identifying_code_" + id + ']').attr("disabled", false);
		//
		$("#no_service_provider_" + id + '_i').removeClass("fa-check-circle-o");
		$("#no_service_provider_" + id + '_i').addClass("fa-circle-o");
		$("#no_service_provider_" + id).addClass("btn-outline");
	}
};