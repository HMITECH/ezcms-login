$(document).ready(function() {
	$('input[type=password]').keyup(function() {
		var password = $(this).val();

    var strength   = 0;
    strength += password.length / 6;
    if ( password.match(/[a-z]/) )      strength++;
    if ( password.match(/[A-Z]/) )      strength++;
    if ( password.match(/\d+/))         strength++;
    if ( password.match(/[^a-z\d]+/) )	strength++;

    strength = strength/6.0;
    strength = Math.max(0, strength);
    strength = Math.min(1, strength);

    // 0..1 => red..green
    var r = Math.floor(255*(1-strength));
    var g = Math.floor(255*(strength));
    var b = Math.floor(255*(1-strength)*strength*4);

    var color = 'rgb( '+r+' , '+g+' , '+b+' )';
    $(this).css('background-color', color);

    var myid = $(this).attr("id");
    if (myid == 'password2') {
        var password1 = $("#password1").val();
        var password2 = $("#password2").val();
        if(password1 == password2) {
            $("#validate-status").text("equal");
        } else {
            $("#validate-status").text("unequal");
        }
    }
	});
});
