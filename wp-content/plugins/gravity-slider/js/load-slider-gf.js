jQuery(function($) {
    if( $('#gform_wrapper_3').length ){
        var select = $( "#input_3_2" );
        var slider = $( "<div id='slider'></div>" ).insertAfter( select ).slider({
            min: 1,
            max: 11,
            range: "min",
            step: .0001,
            animate: "slow",
            value: select[ 0 ].selectedIndex + 1,
            slide: function( event, ui ) {
                select[ 0 ].selectedIndex = ui.value - 1;

                //Calculation for New Weekly Service $ Goal
                var value1 = jQuery('#input_3_7').val();
                var value3 = Number(value1.replace(/[^0-9\.-]+/g,""));
                var value2 = jQuery('#input_3_2').val();
                var finalv = '$' + (value2 * value3);
                var grossv= '$' + (value2 * value3 * 50);
                jQuery("#input_3_6").val(finalv);
                jQuery("#input_3_20").val(grossv);
                //end

                //Calculation for LET'S BREAK IT DOWN section
                var finalv2 = (value2 * value3);
                var value4 = jQuery('#input_3_12').val();
                if ( value4=="" ) {
                    var finalv3 = '$0.00';
                    var value5 = '$0.00';
                    var value7 = '$0.00';
                    var value4 = 0;
                } else {
                    var finalv3v1= (finalv2 / value4);
                    var finalv3v2= Math.trunc(finalv3v1);
                    var finalv3 = '$' + (finalv3v2);
                    var value5v1 = (finalv3v2 * 0.20);
                    var value5v2 = Math.trunc(value5v1);
                    var value5 = '$' + (value5v2);
                    var value6 = ((finalv2 / value4) * 0.20);
                    var value6v1 = (value6 / 2)
                    var value6v2 = Math.trunc(value6v1);
                    var value7 = '$' + (value6v2);
                    var finalv4 = '$' + (value6v2 * value4 * 50);
                }
                jQuery("#input_3_13").val(finalv3);
                jQuery("#input_3_14").val(value5);
                jQuery("#input_3_15").val(value7);
                jQuery("#input_3_19").val(finalv4);
                //end
            }
        });
        $( "#input_3_2" ).on( "change", function() {
            slider.slider( "value", this.selectedIndex + 1 );

            //Calculation for New Weekly Service $ Goal
            var value1 = jQuery('#input_3_7').val();
            var value3 = Number(value1.replace(/[^0-9\.-]+/g,""));
            var value2 = jQuery('#input_3_2').val();
            var finalv = '$' + (value2 * value3);
            var grossv= '$' + (value2 * value3 * 50);
            jQuery("#input_3_6").val(finalv);
            jQuery("#input_3_20").val(grossv);
            //end

            //Calculation for LET'S BREAK IT DOWN section
            var finalv2 = (value2 * value3);
            var value4 = jQuery('#input_3_12').val();
            if ( value4=="" ) {
                var finalv3 = '$0.00';
                var value5 = '$0.00';
                var value7 = '$0.00';
                var value4 = 0;
            } else {
                var finalv3v1= (finalv2 / value4);
                var finalv3v2= Math.trunc(finalv3v1);
                var finalv3 = '$' + (finalv3v2);
                var value5v1 = (finalv3v2 * 0.20);
                var value5v2 = Math.trunc(value5v1);
                var value5 = '$' + (value5v2);
                var value6 = ((finalv2 / value4) * 0.20);
                var value6v1 = (value6 / 2)
                var value6v2 = Math.trunc(value6v1);
                var value7 = '$' + (value6v2);
                var finalv4 = '$' + (value6v2 * value4 * 50);
            }
            jQuery("#input_3_13").val(finalv3);
            jQuery("#input_3_14").val(value5);
            jQuery("#input_3_15").val(value7);
            jQuery("#input_3_19").val(finalv4);
            //end
        });
        $('#input_3_3').on( "change", function() {
            var profit1 = jQuery('#input_3_3').val();
            var profit2 = Math.trunc(profit1);
            slider.slider( "value", profit2+.5 );
            jQuery("#input_3_2").val(profit2);
        });
    }
} );