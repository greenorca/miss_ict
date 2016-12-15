$(document).ready(function(event){
	/* handle todo editor + changes */
	/* http://habibhadi.com/lab/easyeditor/default-example.html */
	$('#todo').on('dblclick',function (){
		if ($('#todo_content')!==undefined){
			$('#todo h2').append('<button id="todo_save">Save</button>');
			$('#todo').append('<div id="editor">'+$('#todo_content').html()+'</div>');
			$('#editor').easyEditor({
  			buttons: ['bold', 'italic', 'link', 'h2', 'h3', 'h4', 'alignleft','code', 'list', 'x', 'source'],
  			
			});
			//attach event handler
			$('#todo_save').on('click', 
				{modul_nr: $('#todo_content').parent().attr('data-id')}, //attaching modul nr
				saveEditor);
			//kick todo_content element
			$('#todo_content').remove();
		}
	});
		
	/* handle changed table cells */
	$('.td_edi').on("dblclick",function () {
		if ($(this).attr('contenteditable')=="false" || $(this).attr('contenteditable')==undefined){
			// focus lost, clean up previous editor cells
			$('.td_edi[contenteditable=true]').each(function(){
				$(this).text($(this).attr('old_value'));
				$(this).attr('contenteditable',"false");
			  $(this).css('background','rgba(255,255,255,0)');
			  $(this).attr('old_value',null);	
			});
			$(this).attr('contenteditable',true);
			$(this).css('background','yellow');
			$(this).attr('old_value',$(this).text());
			$(this).on("keypress",saveAction);
		}
		else {
			$(this).attr('contenteditable',"false");
			$(this).css('background','rgba(255,255,255,0)');		
		}
	});	
	
	/** save todo content editor */
	var saveEditor = function(event){
		$.post('class/ajax.php',{
				update: true,
				modul_id: event.data.modul_nr,
				col: 'todo',
				new_val: $('#editor').html(),
			}, function (data, status) {
						
				if (data=='OK'){
					
				}else {
					window.alert("Update failed:: "+data);
				}
			});
	};	
	
	/* try to save changes on CTRL+Enter */
	var saveAction = function (event) {
		if (event.ctrlKey && event.which==13){
			event.preventDefault();
			$.post('class/ajax.php',{
				update: true,
				modul_id: $(this).parent().attr('data-id'),
				col: $(this).attr('data-col'),
				new_val: $(this).text(),
			}, function (data, status) {
						
				if (data=='OK'){
						$('.td_edi[contenteditable=true]').each(function(){
							$(this).text($(this).text().trim());
							$(this).attr('contenteditable',"false");
							$(this).css('background','rgb(150,255,150)');
							$(this).animate({backgroundColor: "white"},1000);
			  		});
			  		//$('#todo_content').attr('contenteditable',"false");
						//$('#todo_content').css('background','rgb(150,255,150)');
			  		
				}
				else {
					window.alert("Update failed:: "+data);	
				}
			});
		}
	};

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
				var jsonData = $.parseJSON(data);
				$('#right #hanoks').empty();
				$('#right #hanoks').append("<h4>ICT HANOKS</h4>")
				$('#right #hanoks').append("<div id='hanok_data'>"+jsonData[0].hanoks+"</div>");
	    	$('#right #hanoks').find('dd table tbody tr:nth-of-type(odd)').
	    		on("click",showHideHanoks);
	    	$('#right #todo').empty();
	    	$('#right #todo').append("<h2 title='Dblclick to edit, CTRL+ENTER to save changes'>TODO</h2>");
	    	$('#right #todo').attr("data-id",jsonData[0].modul_nr);
	    	if (jsonData[0].todo!== 'undefined'){
		    	$('#right #todo').append('<div data-col="todo" id="todo_content">'+jsonData[0].todo+'</div>');
		    	
		    }
				else{
					$('#right #todo').append('<div data-col="todo" id="todo_content">&nbsp;</div>');
				}		    
		    $('#todo_content').on("keypress",saveAction);
				
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
