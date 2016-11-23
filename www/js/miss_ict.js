$(document).ready(function(event){
	
	/* load module specifications via ajax */
	$('.tr_modul').on("click",function () {
		$('.active').removeClass('active');
		$(this).addClass('active');
		var id = $(this).children()[0].innerHTML;

		/* fetch and display required modules */
		$.post('class/ajax.php',{
			required_mods: id,
			}, function (data, status) {
				$('#right #req_mods').empty();
				$('#right #req_mods').append("<h4>Notwendige Vorkenntnisse</h4>")
				$('#right #req_mods').append("<p><b>Module:</b>"+data+"</p>");
			});				
		
		/* fetch and display hanoks */
		$.post("class/ajax.php",{						
				hanok: id,
			},
			function (data, status) {
				$('#right #hanoks').empty();
				$('#right #hanoks').append("<h4>ICT HANOKS</h4>")
				$('#right #hanoks').append("<div id='hanok_data'>"+data+"</div>");
	    	$('#right #hanoks').find('dd table tbody tr:nth-of-type(odd)').
	    		on("click",showHideHanoks);
				
			});
		/* fill up links to LBV */
		$('#right #lbv').empty();
		$('#right #lbv').append("<h4 data='media/bivo/LBV_"+id+".html'>LBV</h4>");
		$('#right #lbv').append("<a target='new' href='media/toolbox/toolbox_m"+id+".html'><h5>Toolbox (if exists)</h5></a>");
		$('#right #lbv h4').on('mouseover', function () {
			$(this).css( 'cursor', 'pointer' );
		})
		$('#right #lbv h4').on('click', function (event) {
			/*event.removeEvent(this);*/
			$.get($(this).attr('data'), null, function (data, success) {
				if (data !=""){
					$('body').append("<div id='sOverlay'><div id='sCenter'></div></div>");
					$('#sCenter').append('<p style="text-align: center;font-size:0.8em;color:blue;">ESC to close</p>');
					$('#sCenter').append(data);
				}
				
			})
			.fail(function () { //error function
					$('body').append("<div id='sOverlay'><div id='sCenter'></div></div>");
					$('#sCenter').append('<p style="text-align: center;font-size:0.8em;color:blue;">ESC to close</p>');
					$('#sCenter').append('<h3 style="text-align:center; margin-top: 25%;">LBV nicht verfügbar :|</h3>');
				
			})
		});
		});

	/* dump lightbox on key_pressed */
	$('body').keypress(function (event) {
		if (event.key=="Escape") {
			$('#sOverlay').remove();
		}
	});
			
	/* show - hide hanoks */
  	var showHideHanoks = function () {
		var table = $(this).next().find('table');
		var bold = $(this).next().find('b');
		if ($(table).css("display")=="none"){
			 $(table).css("display","block");   		
			 $(bold).css("display","inline");   		
		} else {
			$(table).css("display","none");
			$(bold).css("display","none");   		
			}
	};

	$('#s_fachschaft').on("change", function () {
		var tableRows = $('#t_modules').find('tr.tr_modul');
		switch($('#s_fachschaft').val()) {
			case "": tableRows.fadeIn(); break;
			default:
				$index = 0;
				$(tableRows).each(function () {
					if ($index > 0 && 
						$(this).find('.td_fachschaft').hasClass("fachschaft_"+$('#s_fachschaft').val()) )
					{
							$(this).fadeIn();
					} else {
						$(this).fadeOut();
					}
					$index++;
				});
		}
	});
	
});
